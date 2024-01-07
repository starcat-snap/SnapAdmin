<?php declare(strict_types=1);

namespace SnapAdmin\Core\Migration\V6_6;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1703587036Rule extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1703587036;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE `rule` (
              `id` binary(16) NOT NULL,
              `name` varchar(500) NOT NULL,
              `description` longtext DEFAULT NULL,
              `priority` int(11) NOT NULL,
              `payload` longblob DEFAULT NULL,
              `invalid` tinyint(1) NOT NULL DEFAULT 0,
              `areas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`areas`)),
              `module_types` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`module_types`)),
              `custom_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_fields`)),
              `created_at` datetime(3) NOT NULL,
              `updated_at` datetime(3) DEFAULT NULL,
              PRIMARY KEY (`id`),
              CONSTRAINT `json.rule.module_types` CHECK (json_valid(`module_types`)),
              CONSTRAINT `json.rule.custom_fields` CHECK (json_valid(`custom_fields`))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
