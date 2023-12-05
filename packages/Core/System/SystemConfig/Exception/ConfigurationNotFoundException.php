<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('system-settings')]
class ConfigurationNotFoundException extends SnapAdminHttpException
{
    public function __construct(string $scope)
    {
        parent::__construct(
            'Configuration for scope "{{ scope }}" not found.',
            ['scope' => $scope]
        );
    }

    public function getErrorCode(): string
    {
        return 'SYSTEM__SCOPE_NOT_FOUND';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
