<?php declare(strict_types=1);

namespace SnapAdmin\Core\Migration\V6_6;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1703955978MailTemplate extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1703955978;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE `mail_template_type` (
              `id` BINARY(16) NOT NULL,
              `technical_name` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
              `available_entities` LONGTEXT COLLATE utf8mb4_unicode_ci NULL,
              `created_at` DATETIME(3) NOT NULL,
              `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`id`),
              CONSTRAINT `uniq.mail_template_type.technical_name` UNIQUE (`technical_name`),
              CONSTRAINT `json.mail_template_type.available_entities` CHECK (JSON_VALID(`available_entities`))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');

        $connection->executeStatement('
            CREATE TABLE `mail_template_type_translation` (
              `mail_template_type_id` BINARY(16) NOT NULL,
              `language_id` BINARY(16) NOT NULL,
              `name` VARCHAR(255) NOT NULL,
              `custom_fields` JSON NULL,
              `created_at` DATETIME(3) NOT NULL,
              `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`mail_template_type_id`, `language_id`),
              CONSTRAINT `json.mail_template_type_translation.custom_fields` CHECK (JSON_VALID(`custom_fields`)),
              CONSTRAINT `fk.mail_template_type_translation.mail_template_type_id` FOREIGN KEY (`mail_template_type_id`)
                REFERENCES `mail_template_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.mail_template_type_translation.language_id` FOREIGN KEY (`language_id`)
                REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');

        $connection->executeStatement('
            CREATE TABLE `mail_template` (
              `id` BINARY(16) NOT NULL,
              `mail_template_type_id` BINARY(16) NULL,
              `system_default` TINYINT(1) unsigned NOT NULL DEFAULT \'0\',
              `created_at` DATETIME(3) NOT NULL,
              `updated_at` DATETIME(3),
              PRIMARY KEY (`id`),
              CONSTRAINT `fk.mail_template.mail_template_type_id` FOREIGN KEY (`mail_template_type_id`)
                REFERENCES `mail_template_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            CREATE TABLE `mail_template_translation` (
              `mail_template_id` BINARY(16) NOT NULL,
              `language_id` BINARY(16) NOT NULL,
              `sender_name` VARCHAR(255) DEFAULT NULL,
              `subject` VARCHAR(255) DEFAULT NULL,
              `description` LONGTEXT DEFAULT NULL,
              `content_html` LONGTEXT DEFAULT NULL,
              `content_plain` LONGTEXT DEFAULT NULL,
              `created_at` DATETIME(3) NOT NULL,
              `updated_at` DATETIME(3) DEFAULT NULL,
              PRIMARY KEY (`mail_template_id`, `language_id`),
              CONSTRAINT `fk.mail_template_translation.mail_template_id` FOREIGN KEY (`mail_template_id`)
                REFERENCES `mail_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.mail_template_translation.language_id` FOREIGN KEY (`language_id`)
                REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

         $query = <<<'SQL'
            CREATE TABLE mail_template_media (
              id BINARY(16) NOT NULL,
              mail_template_id BINARY(16) NOT NULL,
              media_id BINARY(16) NOT NULL,
              position INT(11) NOT NULL DEFAULT 1,
              PRIMARY KEY (id),
              CONSTRAINT `fk.mail_template_media.mail_template_id` FOREIGN KEY (`mail_template_id`)
                REFERENCES `mail_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.mail_template_media.media_id` FOREIGN KEY (`media_id`)
                REFERENCES `media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        SQL;

        $connection->executeStatement($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
