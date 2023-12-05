<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Controller\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class ExpectedUserHttpException extends SnapAdminHttpException
{
    public function __construct()
    {
        parent::__construct('For this interaction an authenticated user login is required.');
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__EXPECTED_USER';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}
