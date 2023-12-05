<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Controller\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class PermissionDeniedException extends SnapAdminHttpException
{
    public function __construct()
    {
        parent::__construct('The user does not have the permission to do this action.');
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__PERMISSION_DENIED';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}
