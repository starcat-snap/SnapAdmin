<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Snippet;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<SnippetEntity>
 */
#[Package('core')]
class SnippetCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'snippet_collection';
    }

    protected function getExpectedClass(): string
    {
        return SnippetEntity::class;
    }
}
