<?php declare(strict_types=1);

namespace SnapAdmin\Core\Migration\V6_6;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('core')]
class Migration1703587146AddFlow extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1703587146;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `flow` (
                `id`                    BINARY(16)      NOT NULL,
                `name`                  VARCHAR(255)    COLLATE utf8mb4_unicode_ci  NOT NULL,
                `description`           MEDIUMTEXT      COLLATE utf8mb4_unicode_ci  NULL,
                `event_name`            VARCHAR(255)    NOT NULL,
                `priority`              INT(11)         NOT NULL DEFAULT 1,
                `payload`               LONGBLOB        NULL,
                `invalid`               TINYINT(1)      NOT NULL DEFAULT 0,
                `active`                TINYINT(1)      NOT NULL DEFAULT 0,
                `custom_fields`         JSON            NULL,
                `created_at`            DATETIME(3)     NOT NULL,
                `updated_at`            DATETIME(3)     NULL,
                PRIMARY KEY (`id`),
                INDEX `idx.flow.event_name` (`event_name`, `priority`),
                CONSTRAINT `json.flow.custom_fields` CHECK (JSON_VALID(`custom_fields`))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `flow_sequence` (
                `id`                    BINARY(16)      NOT NULL,
                `flow_id`               BINARY(16)      NOT NULL,
                `parent_id`             BINARY(16)      NULL,
                `rule_id`               BINARY(16)      NULL,
                `action_name`           VARCHAR(255)    NULL,
                `config`                JSON            NULL,
                `position`              INT(11)         NOT NULL DEFAULT 1,
                `display_group`         INT(11)         NOT NULL DEFAULT 1,
                `true_case`             TINYINT(1)      NOT NULL DEFAULT 0,
                `custom_fields`         JSON            NULL,
                `created_at`            DATETIME(3)     NOT NULL,
                `updated_at`            DATETIME(3)     NULL,
                PRIMARY KEY (`id`),
                CONSTRAINT `json.flow_sequence.config` CHECK (JSON_VALID(`config`)),
                CONSTRAINT `json.flow_sequence.custom_fields` CHECK (JSON_VALID(`custom_fields`)),
                CONSTRAINT `fk.flow_sequence.flow_id` FOREIGN KEY (`flow_id`)
                    REFERENCES `flow` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.flow_sequence.rule_id` FOREIGN KEY (`rule_id`)
                    REFERENCES `rule` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
                CONSTRAINT `fk.flow_sequence.parent_id` FOREIGN KEY (`parent_id`)
                    REFERENCES flow_sequence (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
