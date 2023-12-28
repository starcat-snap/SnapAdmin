<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Indexing;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Content\Flow\Dispatching\CachedFlowLoader;
use SnapAdmin\Core\Content\Flow\Dispatching\FlowBuilder;
use SnapAdmin\Core\Framework\Adapter\Cache\CacheInvalidator;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Doctrine\FetchModeHelper;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Doctrine\RetryableQuery;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Uuid\Uuid;

#[Package('services-settings')]
class FlowPayloadUpdater
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Connection $connection,
        private readonly FlowBuilder $flowBuilder,
        private readonly CacheInvalidator $cacheInvalidator
    ) {
    }

    public function update(array $ids): array
    {
        $listFlowSequence = $this->connection->fetchAllAssociative(
            'SELECT LOWER(HEX(`flow`.`id`)) as array_key,
            LOWER(HEX(`flow`.`id`)) as `flow_id`,
            LOWER(HEX(`flow_sequence`.`id`)) as `sequence_id`,
            LOWER(HEX(`flow_sequence`.`parent_id`)) as `parent_id`,
            LOWER(HEX(`flow_sequence`.`rule_id`)) as `rule_id`,
            LOWER(HEX(`flow_sequence`.`app_flow_action_id`)) as `app_flow_action_id`,
            `flow_sequence`.`display_group` as `display_group`,
            `flow_sequence`.`position` as `position`,
            `flow_sequence`.`action_name` as `action_name`,
            `flow_sequence`.`config` as `config`,
            `flow_sequence`.`true_case` as `true_case`
            FROM `flow`
            LEFT JOIN `flow_sequence` ON `flow`.`id` = `flow_sequence`.`flow_id`
            WHERE `flow`.`active` = 1
                AND (`flow_sequence`.`id` IS NULL OR (`flow_sequence`.`rule_id` IS NOT NULL OR `flow_sequence`.`action_name` IS NOT NULL))
                AND `flow`.`id` IN (:ids)',
            ['ids' => Uuid::fromHexToBytesList($ids)],
            ['ids' => ArrayParameterType::BINARY]
        );

        $listFlowSequence = FetchModeHelper::group($listFlowSequence);

        $update = new RetryableQuery(
            $this->connection,
            $this->connection->prepare('UPDATE `flow` SET payload = :payload, invalid = :invalid WHERE `id` = :id')
        );

        $updated = [];
        foreach ($listFlowSequence as $flowId => $flowSequences) {
            usort($flowSequences, fn (array $first, array $second) => [$first['display_group'], $first['parent_id'], $first['true_case'], $first['position']]
                <=> [$second['display_group'], $second['parent_id'], $second['true_case'], $second['position']]);

            $invalid = false;
            $serialized = null;

            try {
                $serialized = serialize($this->flowBuilder->build($flowId, $flowSequences));
            } catch (\Throwable) {
                $invalid = true;
            } finally {
                $update->execute([
                    'id' => Uuid::fromHexToBytes($flowId),
                    'payload' => $serialized,
                    'invalid' => (int) $invalid,
                ]);
            }

            $updated[$flowId] = ['payload' => $serialized, 'invalid' => $invalid];
        }

        $this->cacheInvalidator->invalidate([CachedFlowLoader::KEY]);

        return $updated;
    }
}
