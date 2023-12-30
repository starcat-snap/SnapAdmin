<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Aggregate\DocumentBaseConfig;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<DocumentBaseConfigEntity>
 */
#[Package('content')]
class DocumentBaseConfigCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'document_base_collection';
    }

    protected function getExpectedClass(): string
    {
        return DocumentBaseConfigEntity::class;
    }
}
