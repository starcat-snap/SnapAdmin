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
class Migration1536232731CountryStateCity extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1536232731;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE `country_state_city` (
              `id`          BINARY(16)                              NOT NULL,
              `country_state_id`  BINARY(16)                              NOT NULL,
              `short_code`  VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
              `position`    INT(11)                                 NOT NULL DEFAULT 1,
              `active`      TINYINT(1)                              NOT NULL DEFAULT 1,
              `created_at`  DATETIME(3)                             NOT NULL,
              `updated_at`  DATETIME(3)                             NULL,
              PRIMARY KEY (`id`),
              CONSTRAINT `fk.country_state_city.country_id` FOREIGN KEY (`country_state_id`)
                REFERENCES `country_state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeStatement('
            CREATE TABLE `country_state_city_translation` (
              `country_state_city_id`    BINARY(16)                         NOT NULL,
              `language_id`         BINARY(16)                              NOT NULL,
              `name`                VARCHAR(255) COLLATE utf8mb4_unicode_ci NULL,
              `custom_fields`       JSON                                    NULL,
              `created_at`          DATETIME(3)                             NOT NULL,
              `updated_at`          DATETIME(3)                             NULL,
              PRIMARY KEY (`country_state_city_id`, `language_id`),
              CONSTRAINT `json.country_state_city_translation.custom_fields` CHECK (JSON_VALID(`custom_fields`)),
              CONSTRAINT `fk.country_state_city_translation.country_state_city_id` FOREIGN KEY (`country_state_city_id`)
                REFERENCES `country_state_city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
              CONSTRAINT `fk.country_state_city_translation.language_id` FOREIGN KEY (`language_id`)
                REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
