<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('services-settings')]
class StoreNotAvailableException extends SnapAdminHttpException
{
    public function __construct()
    {
        parent::__construct('Store is not available');
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__STORE_NOT_AVAILABLE';
    }
}
