<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('services-settings')]
class LicenseDomainVerificationException extends SnapAdminHttpException
{
    public function __construct(
        string $domain,
        string $reason = ''
    )
    {
        $reason = $reason ? (' ' . $reason) : '';
        $message = 'License host verification failed for domain "{{ domain }}.{{ reason }}"';
        parent::__construct($message, ['domain' => $domain, 'reason' => $reason]);
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__STORE_LICENSE_DOMAIN_VALIDATION_FAILED';
    }
}
