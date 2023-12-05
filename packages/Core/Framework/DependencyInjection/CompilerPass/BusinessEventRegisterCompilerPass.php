<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DependencyInjection\CompilerPass;

use SnapAdmin\Core\Framework\Event\BusinessEventRegistry;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[Package('core')]
class BusinessEventRegisterCompilerPass implements CompilerPassInterface
{
    /**
     * @param class-string[] $classes
     */
    public function __construct(private readonly array $classes)
    {
    }

    public function process(ContainerBuilder $container): void
    {
        $definition = $container->getDefinition(BusinessEventRegistry::class);
        $definition->addMethodCall('addClasses', [$this->classes]);
    }
}
