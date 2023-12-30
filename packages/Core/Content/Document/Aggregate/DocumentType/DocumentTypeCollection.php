<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Aggregate\DocumentType;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<DocumentTypeEntity>
 */
#[Package('content')]
class DocumentTypeCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'document_type_collection';
    }

    protected function getExpectedClass(): string
    {
        return DocumentTypeEntity::class;
    }
}
