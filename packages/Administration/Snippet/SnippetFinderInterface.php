<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Snippet;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('administration')]
interface SnippetFinderInterface
{
    /**
     * @return array<string, mixed>
     */
    public function findSnippets(string $locale): array;
}
