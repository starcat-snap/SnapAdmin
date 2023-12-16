<?php declare(strict_types=1);

namespace SnapAdmin\Tests\Unit\Administration\Snippet;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
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
}
