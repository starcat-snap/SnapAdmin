<?php declare(strict_types=1);

namespace SnapAdmin\Administration;

use SnapAdmin\Administration\DependencyInjection\AdministrationMigrationCompilerPass;
use SnapAdmin\Core\Framework\Bundle;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @internal
 */
#[Package('administration')]
class Administration extends Bundle
{
    public function getTemplatePriority(): int
    {
        return -1;
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection/'));
        $loader->load('services.xml');

        $container->addCompilerPass(new AdministrationMigrationCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 0);
    }
}
