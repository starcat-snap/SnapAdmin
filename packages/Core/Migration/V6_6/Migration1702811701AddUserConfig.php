<?php declare(strict_types=1);

namespace SnapAdmin\Core\Migration\V6_6;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Migration\MigrationStep;

class Migration1702811701AddUserConfig extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1702811701;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `user_config` (
                `id`                    BINARY(16)      NOT NULL,
                `user_id`               BINARY(16)      NOT NULL,
                `key`                   VARCHAR(255)    NOT NULL,
                `value`                 JSON            NULL,
                `created_at`            DATETIME(3)     NOT NULL,
                `updated_at`            DATETIME(3)     NULL,
                PRIMARY KEY (`id`),
                UNIQUE `uniq.user_id_key` (`user_id`, `key`),
                CONSTRAINT `json.user_config.value` CHECK (JSON_VALID(`value`)),
                CONSTRAINT `fk.user_config.user_id` FOREIGN KEY (`user_id`)
                    REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
