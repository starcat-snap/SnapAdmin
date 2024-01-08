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
            CREATE TABLE `system_config` (
              `id` binary(16) NOT NULL,
              `configuration_key` varchar(255) NOT NULL,
              `configuration_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`configuration_value`)),
              `scope_id` binary(16) DEFAULT NULL,
              `created_at` datetime(3) NOT NULL,
              `updated_at` datetime(3) DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uniq.system_config.configuration_key__scope_id` (`configuration_key`,`scope_id`),
              KEY `fk.system_config.scope_id` (`scope_id`),
              CONSTRAINT `json.system_config.configuration_value` CHECK (json_valid(`configuration_value`))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($query);
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
