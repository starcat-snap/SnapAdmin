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
class Migration1536232840MediaFolder extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1536232840;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE `media_folder` (
              `id` binary(16) NOT NULL,
              `parent_id` binary(16) DEFAULT NULL,
              `default_folder_id` binary(16) DEFAULT NULL,
              `name` varchar(255) DEFAULT NULL,
              `child_count` int(11) unsigned NOT NULL DEFAULT 0,
              `path` longtext DEFAULT NULL,
              `media_folder_configuration_id` binary(16) DEFAULT NULL,
              `use_parent_configuration` tinyint(1) DEFAULT 1,
              `custom_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_fields`)),
              `created_at` datetime(3) NOT NULL,
              `updated_at` datetime(3) DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uniq.media_folder.default_folder_id` (`default_folder_id`),
              KEY `fk.media_folder.parent_id` (`parent_id`),
              CONSTRAINT `fk.media_folder.default_folder_id` FOREIGN KEY (`default_folder_id`) REFERENCES `media_default_folder` (`id`) ON DELETE SET NULL,
              CONSTRAINT `fk.media_folder.parent_id` FOREIGN KEY (`parent_id`) REFERENCES `media_folder` (`id`) ON DELETE CASCADE,
              CONSTRAINT `json.media_folder.custom_fields` CHECK (json_valid(`custom_fields`))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // no destructive changes
    }
}
