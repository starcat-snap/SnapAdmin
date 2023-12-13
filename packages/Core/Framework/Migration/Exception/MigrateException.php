<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Migration\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class MigrateException extends SnapAdminHttpException
{
    public function __construct(
        string $message,
        \Exception $previous
    ) {
        parent::__construct('Migration error: {{ errorMessage }}', ['errorMessage' => $message], $previous);
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__MIGRATION_ERROR';
    }
}
