<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DependencyInjection\CompilerPass;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[Package('core')]
class FilesystemConfigMigrationCompilerPass implements CompilerPassInterface
{
    private const MIGRATED_FS = ['theme', 'asset', 'sitemap'];

    public function process(ContainerBuilder $container): void
    {
        foreach (self::MIGRATED_FS as $fs) {
            $key = sprintf('snap.filesystem.%s', $fs);
            $urlKey = $key . '.url';
            $typeKey = $key . '.type';
            $configKey = $key . '.config';
            if ($container->hasParameter($typeKey)) {
                continue;
            }

            // 6.1 always refers to the main shop url on theme, asset and sitemap.
            $container->setParameter($urlKey, '');
            $container->setParameter($key, '%snap.filesystem.public%');
            $container->setParameter($typeKey, '%snap.filesystem.public.type%');
            $container->setParameter($configKey, '%snap.filesystem.public.config%');
        }

        if (!$container->hasParameter('snap.filesystem.public.url')) {
            $container->setParameter('snap.filesystem.public.url', '%snap.cdn.url%');
        }
    }
}
