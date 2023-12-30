<?php declare(strict_types=1);

namespace SnapAdmin\Core\Migration\V6_6;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1703956048UserRecovery extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1703956048;
    }

    public function update(Connection $connection): void
    {
        $query = <<<'SQL'
            CREATE TABLE IF NOT EXISTS `user_recovery` (
                `id` BINARY(16) NOT NULL,
                `user_id` BINARY(16) NOT NULL,
                `hash` VARCHAR(255) NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `uniq.user_recovery.user_id` UNIQUE (`user_id`),
                CONSTRAINT `fk.user_recovery.user_id` FOREIGN KEY (`user_id`)
                    REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
