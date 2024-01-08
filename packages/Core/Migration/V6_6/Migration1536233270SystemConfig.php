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
class Migration1536233270SystemConfig extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1536233270;
    }

    public function update(Connection $connection): void
    {
        $query = <<<'SQL'
            CREATE TABLE IF NOT EXISTS `system_config` (
                `id` BINARY(16) NOT NULL,
                `configuration_key` VARCHAR(255) NOT NULL,
                `configuration_value` JSON NOT NULL,
                `scope_id` binary(16) DEFAULT NULL,
                `scope` varchar(16) DEFAULT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uniq.system_config.configuration_key__scope_id` (`configuration_key`,`scope`,`scope_id`),
                CONSTRAINT `json.system_config.configuration_value` CHECK (JSON_VALID(`configuration_value`)),
                CONSTRAINT `uniq.system_config.configuration_key` UNIQUE (`configuration_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($query);
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
