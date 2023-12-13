<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * Contains all registered RouteScopes in the system
 */
#[Package('core')]
class RouteScopeRegistry
{
    /**
     * @param AbstractRouteScope[] $routeScopes
     *
     * @internal
     */
    public function __construct(private readonly iterable $routeScopes)
    {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getRouteScope(string $id): AbstractRouteScope
    {
        foreach ($this->routeScopes as $routeScope) {
            if ($routeScope->getId() === $id) {
                return $routeScope;
            }
        }

        throw new \InvalidArgumentException('Unknown route scope requested "' . $id . '"');
    }
}
