<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('services-settings')]
class StoreSignatureValidationException extends SnapAdminHttpException
{
    public function __construct(string $reason)
    {
        parent::__construct(
            'Store signature validation failed. Error: {{ error }}',
            ['error' => $reason]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__STORE_SIGNATURE_INVALID';
    }
}
