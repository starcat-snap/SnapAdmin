<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing\Exception;

use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Routing\RoutingException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated tag:v6.6.0 - will be removed, use RoutingException::missingRequestParameter instead
 */
#[Package('core')]
class MissingRequestParameterException extends RoutingException
{
    public function __construct(
        private readonly string $name,
        private readonly string $path = ''
    ) {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.6.0.0', 'use RoutingException::missingRequestParameter instead')
        );

        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            self::MISSING_REQUEST_PARAMETER_CODE,
            'Parameter "{{ parameterName }}" is missing.',
            ['parameterName' => $name]
        );
    }

    public function getName(): string
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.6.0.0', 'use RoutingException::missingRequestParameter instead')
        );

        return $this->name;
    }

    public function getPath(): string
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.6.0.0', 'use RoutingException::missingRequestParameter instead')
        );

        return $this->path;
    }
}
