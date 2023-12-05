<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @internal
 */
#[Package('core')]
class TestBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RemoveDeprecatedServicesPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 0);

        parent::build($container);
    }
}
