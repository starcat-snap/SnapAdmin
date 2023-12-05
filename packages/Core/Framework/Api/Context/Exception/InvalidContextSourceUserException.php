<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Context\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class InvalidContextSourceUserException extends SnapAdminHttpException
{
    public function __construct(string $contextSource)
    {
        parent::__construct(
            '{{ contextSource }} does not have a valid user ID',
            ['contextSource' => $contextSource]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_CONTEXT_SOURCE_USER';
    }
}
