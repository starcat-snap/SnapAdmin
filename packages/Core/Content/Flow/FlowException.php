<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow;

use SnapAdmin\Core\Framework\HttpException;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

#[Package('services-settings')]
class FlowException extends HttpException
{
    final public const METHOD_NOT_COMPATIBLE = 'METHOD_NOT_COMPATIBLE';

    public static function methodNotCompatible(string $method, string $class): FlowException
    {
        return new self(
            Response::HTTP_BAD_REQUEST,
            self::METHOD_NOT_COMPATIBLE,
            'Method {{ method }} is not compatible for {{ class }} class',
            ['method' => $method, 'class' => $class]
        );
    }
}
