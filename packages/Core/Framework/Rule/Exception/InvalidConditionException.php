<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Rule\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('services-settings')]
class InvalidConditionException extends SnapAdminHttpException
{
    public function __construct(string $conditionName)
    {
        parent::__construct('The condition "{{ condition }}" is invalid.', ['condition' => $conditionName]);
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_CONDITION_ERROR';
    }
}
