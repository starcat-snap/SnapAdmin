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
class Migration1536233280CustomFieldSet extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1536233280;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE `custom_field_set` (
              `id` binary(16) NOT NULL,
              `name` varchar(255) NOT NULL,
              `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`config`)),
              `active` tinyint(1) NOT NULL DEFAULT 1,
              `position` int(11) NOT NULL DEFAULT 1,
              `global` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` datetime(3) NOT NULL,
              `updated_at` datetime(3) DEFAULT NULL,
              PRIMARY KEY (`id`),
              CONSTRAINT `json.custom_field_set.config` CHECK (json_valid(`config`))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
