<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Store\Struct;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Store\Struct\ExtensionStruct;

/**
 * @internal
 */
class ExtensionStructTest extends TestCase
{
    public function testFromArray(): void
    {
        $detailData = $this->getDetailFixture();
        $struct = ExtensionStruct::fromArray($detailData);

        static::assertInstanceOf(ExtensionStruct::class, $struct);
    }

    #[DataProvider('badValuesProvider')]
    public function testItThrowsOnMissingData(array $badValues): void
    {
        static::expectException(\InvalidArgumentException::class);
        ExtensionStruct::fromArray($badValues);
    }

    public static function badValuesProvider(): iterable
    {
        yield [[]];
        yield [['name' => 'foo']];
        yield [['type' => 'foo']];
        yield [['name' => 'foo', 'label' => 'bar']];
        yield [['label' => 'bar', 'type' => 'foobar']];
    }

    private function getDetailFixture(): array
    {
        $content = file_get_contents(__DIR__ . '/../_fixtures/responses/extension-detail.json');

        return json_decode($content, true, 512, \JSON_THROW_ON_ERROR);
    }
}
