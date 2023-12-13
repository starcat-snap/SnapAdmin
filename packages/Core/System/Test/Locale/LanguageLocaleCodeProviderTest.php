<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Test\Locale;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use SnapAdmin\Core\Framework\Test\TestDataCollection;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\System\Locale\LanguageLocaleCodeProvider;
use SnapAdmin\Core\System\Locale\LocaleException;

/**
 * @internal
 */
class LanguageLocaleCodeProviderTest extends TestCase
{
    use IntegrationTestBehaviour;

    private LanguageLocaleCodeProvider $languageLocaleProvider;

    private TestDataCollection $ids;

    protected function setUp(): void
    {
        $this->languageLocaleProvider = $this->getContainer()->get(LanguageLocaleCodeProvider::class);

        $this->ids = new TestDataCollection();

        $this->createData();
    }

    public function testGetLocaleForLanguageId(): void
    {
        static::assertEquals('zh-CN', $this->languageLocaleProvider->getLocaleForLanguageId(Defaults::LANGUAGE_SYSTEM));
        static::assertEquals('language-locale', $this->languageLocaleProvider->getLocaleForLanguageId($this->ids->get('language-child')));
    }

    public function testGetLocaleForLanguageIdThrowsForNotExistingLanguage(): void
    {
        static::expectException(LocaleException::class);
        $this->languageLocaleProvider->getLocaleForLanguageId(Uuid::randomHex());
    }


    private function createData(): void
    {
        $this->getContainer()->get('locale.repository')->create([
            [
                'id' => $this->ids->get('language-locale'),
                'code' => 'language-locale',
                'name' => 'language-locale',
                'territory' => 'language-locale',
            ],
        ], Context::createDefaultContext());

        $data = [
            [
                'id' => $this->ids->create('language-parent'),
                'name' => 'parent',
                'localeId' => $this->ids->get('language-locale'),
                'translationCodeId' => $this->ids->get('language-locale'),
            ],
            [
                'id' => $this->ids->create('language-child'),
                'name' => 'child',
                'parentId' => $this->ids->create('language-parent'),
                'localeId' => $this->ids->get('language-locale'),
                'translationCodeId' => null,
            ],
        ];

        $this->getContainer()->get('language.repository')
            ->create($data, Context::createDefaultContext());
    }
}
