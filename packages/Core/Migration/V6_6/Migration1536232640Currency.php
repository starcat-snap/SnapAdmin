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
class Migration1536232640Currency extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1536232640;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE `currency` (
              `id` binary(16) NOT NULL,
              `iso_code` char(3) NOT NULL,
              `factor` double NOT NULL,
              `symbol` varchar(255) NOT NULL,
              `position` int(11) NOT NULL DEFAULT 1,
              `created_at` datetime(3) NOT NULL,
              `updated_at` datetime(3) DEFAULT NULL,
              `item_rounding` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`item_rounding`)),
              `total_rounding` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`total_rounding`)),
              `tax_free_from` double DEFAULT 0,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uniq.currency.iso_code` (`iso_code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            CREATE TABLE `currency_translation` (
              `currency_id` binary(16) NOT NULL,
              `language_id` binary(16) NOT NULL,
              `short_name` varchar(255) DEFAULT NULL,
              `name` varchar(255) DEFAULT NULL,
              `custom_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_fields`)),
              `created_at` datetime(3) NOT NULL,
              `updated_at` datetime(3) DEFAULT NULL,
              PRIMARY KEY (`currency_id`,`language_id`),
              KEY `fk.currency_translation.language_id` (`language_id`),
              CONSTRAINT `fk.currency_translation.currency_id` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.currency_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `json.currency_translation.custom_fields` CHECK (json_valid(`custom_fields`))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
