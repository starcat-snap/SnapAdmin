<?php declare(strict_types=1);

namespace SnapAdmin\Tests\Unit\Administration\Snippet;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Administration\Snippet\SnippetException;
use SnapAdmin\Administration\Snippet\SnippetFinder;
use SnapAdmin\Core\Kernel;

/**
 * @internal
 */
#[CoversClass(SnippetFinder::class)]
class SnippetFinderTest extends TestCase
{
    public function testFindSnippetsFromAppNoSnippetsAdded(): void
    {
        $snippetFinder = new SnippetFinder(
            $this->createMock(Kernel::class)
        );

        $snippets = $snippetFinder->findSnippets('zh-CN');
        static::assertArrayNotHasKey('my-custom-snippet-key', $snippets);
    }

    /**
     * @param array<string, mixed> $existingSnippets
     * @param array<string, mixed> $appSnippets
     * @param list<string> $duplicatedSnippets
     */
    #[DataProvider('validateAppSnippetsExceptionDataProvider')]
    public function testValidateSnippets(array $existingSnippets, array $appSnippets, array $duplicatedSnippets): void
    {
        $exceptionWasThrown = false;
        $expectedExceptionMessage = 'The following keys on the first level are duplicated and can not be overwritten: ' . implode(', ', $duplicatedSnippets);

        $snippetFinder = new SnippetFinder(
            $this->createMock(Kernel::class)
        );

        $reflectionClass = new \ReflectionClass(SnippetFinder::class);
        $reflectionMethod = $reflectionClass->getMethod('validateAppSnippets');

        try {
            $reflectionMethod->invoke($snippetFinder, $existingSnippets, $appSnippets);
            /** @phpstan-ignore-next-line does not check that a SnippetException will be thrown */
        } catch (SnippetException $exception) {
            static::assertEquals($expectedExceptionMessage, $exception->getMessage());

            $exceptionWasThrown = true;
        } finally {
            /** @phpstan-ignore-next-line does not check that $exceptionWasThrown might change */
            static::assertTrue($exceptionWasThrown, 'Expected exception with the following message to be thrown: ' . $expectedExceptionMessage);
        }
    }

    /**
     * @return array<string, array{existingSnippets: array<string, mixed>, appSnippets: array<string, mixed>, duplicatedSnippets: list<string>}>
     */
    public static function validateAppSnippetsExceptionDataProvider(): iterable
    {
        yield 'Throw exception if existing snippets will be overwritten' => [
            'existingSnippets' => [
                'core' => [],
            ],
            'appSnippets' => [
                'my-app-snippets' => [],
                'core' => [
                    'foo' => 'this will extend or overwrite the core',
                ],
            ],
            'duplicatedSnippets' => [
                'core',
            ],
        ];
    }

    /**
     * @return array<string, array{before: array<string, mixed>, after: array<string, mixed>}>
     */
    public static function sanitizeAppSnippetDataProvider(): iterable
    {
        yield 'Test it sanitises app snippets' => [
            'before' => [
                'foo' => [
                    'bar' => [
                        'bar' => '<h1>value</h1>',
                    ],
                ],
            ],
            'after' => [
                'foo' => [
                    'bar' => [
                        'bar' => 'value',
                    ],
                ],
            ],
        ];
    }
}
