<?php declare(strict_types=1);

namespace SnapAdmin\Core\Profiling\Compiler;

use Composer\InstalledVersions;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Profiling\Controller\ProfilerController;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @codeCoverageIgnore It's not possible to test without hacky solutions and relying on internals
 */
#[Package('core')]
class RemoveDevServices implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!InstalledVersions::isInstalled('symfony/web-profiler-bundle') || !$container->hasDefinition('profiler')) {
            $container->removeDefinition(ProfilerController::class);
        }
    }
}
