<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Increment;

use SnapAdmin\Core\Framework\HttpException;
use SnapAdmin\Core\Framework\Increment\Exception\IncrementGatewayNotFoundException;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class IncrementException extends HttpException
{
    public const KEY_PARAMETER_IS_MISSING = 'FRAMEWORK__KEY_PARAMETER_IS_MISSING';
    public const CLUSTER_PARAMETER_IS_MISSING = 'FRAMEWORK__CLUSTER_PARAMETER_IS_MISSING';

    public static function keyParameterIsMissing(): self
    {
        return new self(
            Response::HTTP_BAD_REQUEST,
            self::KEY_PARAMETER_IS_MISSING,
            'Parameter "key" is missing.',
        );
    }

    public static function clusterParameterIsMissing(): self
    {
        return new self(
            Response::HTTP_BAD_REQUEST,
            self::CLUSTER_PARAMETER_IS_MISSING,
            'Parameter "cluster" is missing.',
        );
    }

    public static function gatewayNotFound(string $pool): SnapAdminHttpException
    {
        return new IncrementGatewayNotFoundException($pool);
    }
}
