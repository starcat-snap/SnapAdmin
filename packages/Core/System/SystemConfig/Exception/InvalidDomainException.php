<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('system-settings')]
class InvalidDomainException extends SnapAdminHttpException
{
    public function __construct(string $domain)
    {
        parent::__construct('Invalid domain \'{{ domain }}\'', ['domain' => $domain]);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'SYSTEM__INVALID_DOMAIN';
    }
}
