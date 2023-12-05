<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Context\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class InvalidContextSourceException extends SnapAdminHttpException
{
    public function __construct(
        string $expected,
        string $actual
    )
    {
        parent::__construct(
            'Expected ContextSource of "{{expected}}", but got "{{actual}}".',
            ['expected' => $expected, 'actual' => $actual]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_CONTEXT_SOURCE';
    }
}
