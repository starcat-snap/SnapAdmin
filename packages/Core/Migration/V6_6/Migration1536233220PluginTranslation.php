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
class Migration1536233220PluginTranslation extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1536233220;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE `plugin_translation` (
                `plugin_id`          BINARY(16)  NOT NULL,
                `language_id`        BINARY(16)  NOT NULL,
                `label`              VARCHAR(255) COLLATE utf8mb4_unicode_ci NULL,
                `description`        LONGTEXT COLLATE utf8mb4_unicode_ci NULL,
                `manufacturer_link`  TEXT COLLATE utf8mb4_unicode_ci NULL,
                `support_link`       TEXT COLLATE utf8mb4_unicode_ci NULL,
                `custom_fields`      JSON        NULL,
                `created_at`         DATETIME(3) NOT NULL,
                `updated_at`         DATETIME(3) NULL,
                PRIMARY KEY (`plugin_id`, `language_id`),
                CONSTRAINT `json.plugin_translation.custom_fields` CHECK (JSON_VALID(`custom_fields`)),
                CONSTRAINT `fk.plugin_translation.plugin_id` FOREIGN KEY (`plugin_id`)
                  REFERENCES `plugin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.plugin_translation.language_id` FOREIGN KEY (`language_id`)
                  REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            )  ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
