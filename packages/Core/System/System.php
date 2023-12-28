<?php declare(strict_types=1);

namespace SnapAdmin\Core\System;

use SnapAdmin\Core\Framework\Bundle;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\CustomEntity\CustomEntityRegistrar;
use SnapAdmin\Core\System\DependencyInjection\CompilerPass\RedisNumberRangeIncrementerCompilerPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @internal
 */
#[Package('core')]
class System extends Bundle
{
    public function getTemplatePriority(): int
    {
        return -1;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection/'));
        $loader->load('custom_entity.xml');
        $loader->load('locale.xml');
        $loader->load('user.xml');
        $loader->load('configuration.xml');
        $loader->load('snippet.xml');
        $loader->load('state_machine.xml');
        $loader->load('number_range.xml');
                $container->addCompilerPass(new RedisNumberRangeIncrementerCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 0);

    }

    public function boot(): void
    {
        parent::boot();

        \assert($this->container instanceof ContainerInterface, 'Container is not set yet, please call setContainer() before calling boot(), see `src/Core/Kernel.php:186`.');

        $this->container->get(CustomEntityRegistrar::class)->register();
    }
}
