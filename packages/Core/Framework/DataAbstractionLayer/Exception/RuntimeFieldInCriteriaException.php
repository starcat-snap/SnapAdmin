<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class RuntimeFieldInCriteriaException extends SnapAdminHttpException
{
    public function __construct(string $field)
    {
        parent::__construct(
            'Field {{ field }} is a Runtime field and cannot be used in a criteria',
            ['field' => $field]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__RUNTIME_FIELD_IN_CRITERIA';
    }
}
