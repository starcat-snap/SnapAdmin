<?php declare(strict_types=1);

namespace SnapAdmin\Core\Migration\V6_6;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Content\MailTemplate\MailTemplateTypes;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Doctrine\MultiInsertQueryQueue;
use SnapAdmin\Core\Framework\Migration\MigrationStep;
use SnapAdmin\Core\Framework\Uuid\Uuid;

class Migration1703957048BasicData extends MigrationStep
{

    /**
     * @var array<string, array{id: string, name: string, nameDe: string, availableEntities: array<string, string|null>}>|null
     */
    private ?array $mailTypes = null;

    public function getCreationTimestamp(): int
    {
        return 1703957048;
    }

    public function update(Connection $connection): void
    {
        $hasData = $connection->executeQuery('SELECT 1 FROM `language` LIMIT 1')->fetchAssociative();
        if ($hasData) {
            return;
        }
        $this->createLanguage($connection);
        $this->createDefaultSnippetSets($connection);
        $this->createDefaultMediaFolders($connection);
        $this->createSystemConfigOptions($connection);
        $this->createNumberRanges($connection);
    }


    public function updateDestructive(Connection $connection): void
    {
    }

    private function createNumberRanges(Connection $connection): void
    {
        $definitionNumberRangeTypes = [
            'user' => [
                'id' => Uuid::randomHex(),
                'global' => 0,
                'nameZh' => '用户',
            ],
        ];
        $definitionNumberRanges = [
            'user' => [
                'id' => Uuid::randomHex(),
                'name' => '用户',
                'global' => 1,
                'typeId' => $definitionNumberRangeTypes['user']['id'],
                'pattern' => '{n}',
                'start' => 10000,
            ],
        ];
        $languageZh = Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM);
        foreach ($definitionNumberRangeTypes as $typeName => $numberRangeType) {
            $connection->insert(
                'number_range_type',
                [
                    'id' => Uuid::fromHexToBytes($numberRangeType['id']),
                    'global' => $numberRangeType['global'],
                    'technical_name' => $typeName,
                    'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                ]
            );
            $connection->insert(
                'number_range_type_translation',
                [
                    'number_range_type_id' => Uuid::fromHexToBytes($numberRangeType['id']),
                    'type_name' => $numberRangeType['nameZh'],
                    'language_id' => $languageZh,
                    'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                ]
            );
        }
        foreach ($definitionNumberRanges as $numberRange) {
            $connection->insert(
                'number_range',
                [
                    'id' => Uuid::fromHexToBytes($numberRange['id']),
                    'global' => $numberRange['global'],
                    'type_id' => Uuid::fromHexToBytes($numberRange['typeId']),
                    'pattern' => $numberRange['pattern'],
                    'start' => $numberRange['start'],
                    'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                ]
            );
            $connection->insert(
                'number_range_translation',
                [
                    'number_range_id' => Uuid::fromHexToBytes($numberRange['id']),
                    'name' => $numberRange['name'],
                    'language_id' => $languageZh,
                    'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                ]
            );
        }
    }

    private function createSystemConfigOptions(Connection $connection): void
    {
        $connection->insert('system_config', [
            'id' => Uuid::randomBytes(),
            'configuration_key' => 'core.store.apiUri',
            'configuration_value' => '{"_value": "https://api.snapadmin.net"}',
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
        $connection->insert('system_config', [
            'id' => Uuid::randomBytes(),
            'configuration_key' => 'core.userPermission.passwordMinLength',
            'configuration_value' => '{"_value": 8}',
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
    }

    private function createDefaultMediaFolders(Connection $connection): void
    {
        $queue = new MultiInsertQueryQueue($connection);
        $queue->addInsert('media_default_folder', ['id' => Uuid::randomBytes(), 'entity' => 'user', 'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT)]);
        $queue->execute();

        $notCreatedDefaultFolders = $connection->executeQuery('
            SELECT `media_default_folder`.`id` default_folder_id, `media_default_folder`.`entity` entity
            FROM `media_default_folder`
                LEFT JOIN `media_folder` ON `media_folder`.`default_folder_id` = `media_default_folder`.`id`
            WHERE `media_folder`.`id` IS NULL
        ')->fetchAllAssociative();

        foreach ($notCreatedDefaultFolders as $notCreatedDefaultFolder) {
            $this->createDefaultFolder(
                $connection,
                $notCreatedDefaultFolder['default_folder_id'],
                $notCreatedDefaultFolder['entity']
            );
        }
    }

    private function createDefaultFolder(Connection $connection, string $defaultFolderId, string $entity): void
    {
        $connection->transactional(function (Connection $connection) use ($defaultFolderId, $entity): void {
            $configurationId = Uuid::randomBytes();
            $folderId = Uuid::randomBytes();
            $folderName = $this->getMediaFolderName($entity);
            $private = 0;
            if ($entity === 'document') {
                $private = 1;
            }
            $connection->executeStatement('
                INSERT INTO `media_folder_configuration` (`id`, `thumbnail_quality`, `create_thumbnails`, `private`, created_at)
                VALUES (:id, 80, 1, :private, :createdAt)
            ', [
                'id' => $configurationId,
                'createdAt' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                'private' => $private,
            ]);

            $connection->executeStatement('
                INSERT into `media_folder` (`id`, `name`, `default_folder_id`, `media_folder_configuration_id`, `use_parent_configuration`, `child_count`, `created_at`)
                VALUES (:folderId, :folderName, :defaultFolderId, :configurationId, 0, 0, :createdAt)
            ', [
                'folderId' => $folderId,
                'folderName' => $folderName,
                'defaultFolderId' => $defaultFolderId,
                'configurationId' => $configurationId,
                'createdAt' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);
        });
    }

    private function getMediaFolderName(string $entity): string
    {
        $capitalizedEntityParts = array_map(
            static fn($part) => ucfirst((string)$part),
            explode('_', $entity)
        );

        return implode(' ', $capitalizedEntityParts) . ' Media';
    }

    private function createDefaultSnippetSets(Connection $connection): void
    {
        $queue = new MultiInsertQueryQueue($connection);

        $queue->addInsert('snippet_set', ['id' => Uuid::randomBytes(), 'name' => 'BASE zh-CN', 'base_file' => 'messages.zh-CN', 'iso' => 'zh-CN', 'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT)]);

        $queue->execute();
    }

    private function createLanguage(Connection $connection): void
    {
        $localeZh = Uuid::randomBytes();
        $languageZh = Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM);
        $connection->insert('locale', ['id' => $localeZh, 'code' => 'zh-CN', 'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT)]);
        $connection->insert('language', [
            'id' => $languageZh,
            'name' => 'Chinese',
            'locale_id' => $localeZh,
            'translation_code_id' => $localeZh,
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
        $connection->insert('locale_translation', [
            'locale_id' => $localeZh,
            'language_id' => $languageZh,
            'name' => '中文',
            'territory' => '中国',
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
    }
}
