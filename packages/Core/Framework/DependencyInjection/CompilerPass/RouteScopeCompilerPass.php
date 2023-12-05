<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DependencyInjection\CompilerPass;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Routing\AbstractRouteScope;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[Package('core')]
class RouteScopeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $routeScopeDefinitions = $container->findTaggedServiceIds('snap.route_scope');

        $apiPrefixes = [];
        foreach (array_keys($routeScopeDefinitions) as $definition) {
            $routeScope = $container->get($definition);

            if (!$routeScope instanceof AbstractRouteScope) {
                continue;
            }

            $apiPrefixes = array_merge($apiPrefixes, $routeScope->getRoutePrefixes());
        }

        $container->setParameter('snap.routing.registered_api_prefixes', $apiPrefixes);
    }
}
