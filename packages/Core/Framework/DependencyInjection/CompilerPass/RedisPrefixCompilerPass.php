<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DependencyInjection\CompilerPass;

use SnapAdmin\Core\Framework\Adapter\Cache\SnapAdminRedisAdapter;
use SnapAdmin\Core\Framework\Adapter\Cache\SnapAdminRedisTagAwareAdapter;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 */
#[Package('core')]
class RedisPrefixCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $ids = [
            'cache.adapter.redis' => SnapAdminRedisAdapter::class,
            'cache.adapter.redis_tag_aware' => SnapAdminRedisTagAwareAdapter::class,
        ];

        foreach ($ids as $id => $class) {
            if (!$container->hasDefinition($id)) {
                continue;
            }

            $definition = $container->getDefinition($id);
            $definition->setClass($class);
            $definition->addArgument($container->getParameter('snap.cache.redis_prefix'));
        }
    }
}
