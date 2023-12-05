<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('system-settings')]
class InvalidKeyException extends SnapAdminHttpException
{
    public function __construct(string $key)
    {
        parent::__construct('Invalid key \'{{ key }}\'', ['key' => $key]);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'SYSTEM__INVALID_KEY';
    }
}
