<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing;

use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\Api\Context\SystemSource;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\PlatformRequest;
use Symfony\Component\HttpFoundation\Request;

#[Package('core')]
class ApiRouteScope extends AbstractRouteScope implements ApiContextRouteScopeDependant
{
    final public const ID = 'api';

    /**
     * @var array<string>
     */
    protected $allowedPaths = ['api', 'sw-domain-hash.html'];

    public function isAllowed(Request $request): bool
    {
        /** @var Context $context */
        $context = $request->attributes->get(PlatformRequest::ATTRIBUTE_CONTEXT_OBJECT);
        $authRequired = $request->attributes->get('auth_required', true);
        $source = $context->getSource();

        if (!$authRequired) {
            return $source instanceof SystemSource || $source instanceof AdminApiSource;
        }

        return $context->getSource() instanceof AdminApiSource;
    }

    public function getId(): string
    {
        return self::ID;
    }
}
