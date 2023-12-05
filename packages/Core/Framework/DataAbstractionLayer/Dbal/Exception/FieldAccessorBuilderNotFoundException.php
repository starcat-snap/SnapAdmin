<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class FieldAccessorBuilderNotFoundException extends SnapAdminHttpException
{
    public function __construct(string $field)
    {
        parent::__construct(
            'The field accessor builder for field {{ field }} was not found.',
            ['field' => $field]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__FIELD_ACCESSOR_BUILDER_NOT_FOUND';
    }
}
