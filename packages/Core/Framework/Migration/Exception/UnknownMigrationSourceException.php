<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Migration\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class UnknownMigrationSourceException extends SnapAdminHttpException
{
    public function __construct(private readonly string $name)
    {
        parent::__construct(
            'No source registered for "{{ name }}"',
            ['name' => $name]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_MIGRATION_SOURCE';
    }

    public function getParameters(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
