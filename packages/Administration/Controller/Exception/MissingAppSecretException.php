<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Controller\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('administration')]
class MissingAppSecretException extends SnapAdminHttpException
{
    public function __construct()
    {
        parent::__construct('Failed to retrieve app secret.');
    }

    public function getErrorCode(): string
    {
        return 'ADMINISTRATION__MISSING_APP_SECRET';
    }
}
