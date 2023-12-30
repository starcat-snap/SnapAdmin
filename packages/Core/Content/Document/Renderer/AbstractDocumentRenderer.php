<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Renderer;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Content\Document\Struct\DocumentGenerateOperation;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Uuid\Uuid;

#[Package('content')]
abstract class AbstractDocumentRenderer
{
    abstract public function supports(): string;

    /**
     * @param DocumentGenerateOperation[] $operations
     */
    abstract public function render(array $operations, Context $context, DocumentRendererConfig $rendererConfig): RendererResult;

    abstract public function getDecorated(): AbstractDocumentRenderer;

    /**
     * @param array<int, string> $ids
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getOrdersLanguageId(array $ids, string $versionId, Connection $connection): array
    {
        return $connection->fetchAllAssociative(
            '
            SELECT LOWER(HEX(language_id)) as language_id, GROUP_CONCAT(DISTINCT LOWER(HEX(id))) as ids
            FROM `order`
            WHERE `id` IN (:ids)
            AND `version_id` = :versionId
            AND `language_id` IS NOT NULL
            GROUP BY `language_id`',
            ['ids' => Uuid::fromHexToBytesList($ids), 'versionId' => Uuid::fromHexToBytes($versionId)],
            ['ids' => ArrayParameterType::BINARY]
        );
    }
}
