<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<DocumentEntity>
 */
#[Package('content')]
class DocumentCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'document_collection';
    }

    protected function getExpectedClass(): string
    {
        return DocumentEntity::class;
    }
}
