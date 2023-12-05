<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Filesystem\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class DuplicateFilesystemFactoryException extends SnapAdminHttpException
{
    public function __construct(string $type)
    {
        parent::__construct('The type of factory "{{ type }}" must be unique.', ['type' => $type]);
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__DUPLICATE_FILESYSTEM_FACTORY';
    }
}
