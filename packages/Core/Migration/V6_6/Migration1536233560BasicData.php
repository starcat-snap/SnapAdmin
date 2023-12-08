<?php declare(strict_types=1);

namespace SnapAdmin\Core\Migration\V6_6;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Doctrine\MultiInsertQueryQueue;
use SnapAdmin\Core\Framework\Migration\MigrationStep;
use SnapAdmin\Core\Framework\Uuid\Uuid;

class Migration1536233560BasicData extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1536233560;
    }

    public function update(Connection $connection): void
    {
        $hasData = $connection->executeQuery('SELECT 1 FROM `language` LIMIT 1')->fetchAssociative();
        if ($hasData) {
            return;
        }
        $this->createLanguage($connection);
        $this->createDefaultSnippetSets($connection);
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

    public function updateDestructive(Connection $connection): void
    {
        // TODO: Implement updateDestructive() method.
    }
}
