<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 */
#[Package('core')]
class RemoveDeprecatedServicesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if ($definition->isDeprecated()) {
                $container->removeDefinition($id);
            }
        }
    }
}
