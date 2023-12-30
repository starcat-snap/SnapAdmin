<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Aggregate\DocumentTypeTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<DocumentTypeTranslationEntity>
 */
#[Package('content')]
class DocumentTypeTranslationCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'document_type_translation_collection';
    }

    protected function getExpectedClass(): string
    {
        return DocumentTypeTranslationEntity::class;
    }
}
