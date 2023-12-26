<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\DependencyInjection\CompilerPass;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\NumberRange\ValueGenerator\Pattern\IncrementStorage\IncrementRedisStorage;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[Package('core')]
class RedisNumberRangeIncrementerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->getParameter('snap.number_range.redis_url')) {
            $container->removeDefinition('snap.number_range.redis');
            $container->removeDefinition(IncrementRedisStorage::class);
        }
    }
}
