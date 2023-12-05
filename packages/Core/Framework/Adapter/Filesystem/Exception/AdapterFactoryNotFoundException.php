<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Filesystem\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class AdapterFactoryNotFoundException extends SnapAdminHttpException
{
    public function __construct(string $type)
    {
        parent::__construct(
            'Adapter factory for type "{{ type }}" was not found.',
            ['type' => $type]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__FILESYSTEM_ADAPTER_NOT_FOUND';
    }
}
