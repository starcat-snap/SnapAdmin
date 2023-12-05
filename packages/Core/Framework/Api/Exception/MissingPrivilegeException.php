<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class MissingPrivilegeException extends SnapAdminHttpException
{
    final public const MISSING_PRIVILEGE_ERROR = 'FRAMEWORK__MISSING_PRIVILEGE_ERROR';

    public function __construct(array $privilege = [])
    {
        $errorMessage = json_encode([
            'message' => 'Missing privilege',
            'missingPrivileges' => $privilege,
        ], \JSON_THROW_ON_ERROR);

        parent::__construct($errorMessage ?: '');
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }

    public function getErrorCode(): string
    {
        return self::MISSING_PRIVILEGE_ERROR;
    }
}
