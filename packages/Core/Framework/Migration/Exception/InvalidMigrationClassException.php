<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Migration\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class InvalidMigrationClassException extends SnapAdminHttpException
{
    public function __construct(
        string $class,
        string $path
    ) {
        parent::__construct(
            'Unable to load migration {{ class }} at path {{ path }}',
            ['class' => $class, 'path' => $path]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_MIGRATION';
    }
}
