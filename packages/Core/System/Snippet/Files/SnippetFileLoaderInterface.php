<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Snippet\Files;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('system-settings')]
interface SnippetFileLoaderInterface
{
    public function loadSnippetFilesIntoCollection(SnippetFileCollection $snippetFileCollection): void;
}
