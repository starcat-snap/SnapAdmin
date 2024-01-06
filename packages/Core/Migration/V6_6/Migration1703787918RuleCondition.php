<?php declare(strict_types=1);

namespace SnapAdmin\Core\Migration\V6_6;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 *
 * @codeCoverageIgnore
 */
#[Package('core')]
class Migration1703787918RuleCondition extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1703787918;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE `rule_condition` (
              `id` binary(16) NOT NULL,
              `type` varchar(255) NOT NULL,
              `rule_id` binary(16) NOT NULL,
              `script_id` binary(16) DEFAULT NULL,
              `parent_id` binary(16) DEFAULT NULL,
              `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`value`)),
              `position` int(11) NOT NULL DEFAULT 0,
              `custom_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_fields`)),
              `created_at` datetime(3) NOT NULL,
              `updated_at` datetime(3) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `fk.rule_condition.rule_id` (`rule_id`),
              KEY `fk.rule_condition.parent_id` (`parent_id`),
              KEY `fk.rule_condition.script_id` (`script_id`),
              CONSTRAINT `fk.rule_condition.parent_id` FOREIGN KEY (`parent_id`) REFERENCES `rule_condition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.rule_condition.rule_id` FOREIGN KEY (`rule_id`) REFERENCES `rule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `json.rule_condition.value` CHECK (json_valid(`value`)),
              CONSTRAINT `json.rule_condition.custom_fields` CHECK (json_valid(`custom_fields`))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
