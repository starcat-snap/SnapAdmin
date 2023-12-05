<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('services-settings')]
class ShopSecretInvalidException extends SnapAdminHttpException
{
    public function __construct()
    {
        parent::__construct('Store shop secret is invalid');
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__STORE_SHOP_SECRET_INVALID';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}
