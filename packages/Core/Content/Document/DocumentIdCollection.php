<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<DocumentIdStruct>
 */
#[Package('content')]
class DocumentIdCollection extends Collection
{
    public function getApiAlias(): string
    {
        return 'document_id_collection';
    }

    protected function getExpectedClass(): ?string
    {
        return DocumentIdStruct::class;
    }
}
