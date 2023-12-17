<?php declare(strict_types=1);

namespace SnapAdmin\Core\Migration\V6_6;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Migration\MigrationStep;

class Migration1702811047UserAccessKey extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1702811047;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
                    CREATE TABLE `user_access_key` (
                      `id` binary(16) NOT NULL,
                      `user_id` binary(16) NOT NULL,
                      `access_key` varchar(255) NOT NULL,
                      `secret_access_key` varchar(255) NOT NULL,
                      `last_usage_at` datetime(3) DEFAULT NULL,
                      `custom_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_fields`)),
                      `created_at` datetime(3) NOT NULL,
                      `updated_at` datetime(3) DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `idx.user_access_key.user_id_` (`user_id`),
                      KEY `idx.user_access_key.access_key` (`access_key`),
                      CONSTRAINT `fk.user_access_key.user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                      CONSTRAINT `json.user_access_key.custom_fields` CHECK (json_valid(`custom_fields`))
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
