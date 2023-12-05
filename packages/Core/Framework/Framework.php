<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework;

use SnapAdmin\Core\Framework\Adapter\Cache\CacheValueCompressor;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\ExtensionRegistry;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\AssetBundleRegistrationCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\AssetRegistrationCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\AutoconfigureCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\DefaultTransportCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\DemodataCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\DisableTwigCacheWarmerCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\EntityCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\FeatureFlagCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\FilesystemConfigMigrationCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\FrameworkMigrationReplacementCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\HttpCacheConfigCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\RateLimiterCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\RedisPrefixCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\RouteScopeCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\TwigEnvironmentCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\CompilerPass\TwigLoaderConfigCompilerPass;
use SnapAdmin\Core\Framework\DependencyInjection\FrameworkExtension;
use SnapAdmin\Core\Framework\Increment\IncrementerGatewayCompilerPass;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Migration\MigrationCompilerPass;
use SnapAdmin\Core\Framework\Test\RateLimiter\DisableRateLimiterCompilerPass;
use SnapAdmin\Core\Kernel;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\ClosureLoader;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @internal
 */
#[Package('core')]
class Framework extends Bundle
{
    public function getTemplatePriority(): int
    {
        return -1;
    }

    public function getContainerExtension(): Extension
    {
        return new FrameworkExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        $container->setParameter('locale', 'zh-CN');
        $environment = (string)$container->getParameter('kernel.environment');

        $this->buildConfig($container, $environment);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection/'));
        $loader->load('services.xml');
        $loader->load('acl.xml');
        $loader->load('cache.xml');
        $loader->load('api.xml');
        $loader->load('custom-field.xml');
        $loader->load('data-abstraction-layer.xml');
        $loader->load('event.xml');
        $loader->load('hydrator.xml');
        $loader->load('filesystem.xml');
        $loader->load('message-queue.xml');
        $loader->load('plugin.xml');
        $loader->load('scheduled-task.xml');
        $loader->load('store.xml');
        $loader->load('language.xml');
        $loader->load('update.xml');
        $loader->load('rate-limiter.xml');
        $loader->load('increment.xml');

        if ($container->getParameter('kernel.environment') === 'test') {
            $loader->load('services_test.xml');
            $loader->load('store_test.xml');
        }

        // make sure to remove services behind a feature flag, before some other compiler passes may reference them, therefore the high priority
        $container->addCompilerPass(new FeatureFlagCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1000);
        $container->addCompilerPass(new EntityCompilerPass());
        $container->addCompilerPass(new MigrationCompilerPass(), PassConfig::TYPE_AFTER_REMOVING);
        $container->addCompilerPass(new DisableTwigCacheWarmerCompilerPass());
        $container->addCompilerPass(new DefaultTransportCompilerPass());
        $container->addCompilerPass(new TwigLoaderConfigCompilerPass());
        $container->addCompilerPass(new TwigEnvironmentCompilerPass());
        $container->addCompilerPass(new RouteScopeCompilerPass());
        $container->addCompilerPass(new AssetRegistrationCompilerPass());
        $container->addCompilerPass(new AssetBundleRegistrationCompilerPass());
        $container->addCompilerPass(new FilesystemConfigMigrationCompilerPass());
        $container->addCompilerPass(new RateLimiterCompilerPass());
        $container->addCompilerPass(new IncrementerGatewayCompilerPass());
        $container->addCompilerPass(new RedisPrefixCompilerPass());
        $container->addCompilerPass(new AutoconfigureCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1000);
        $container->addCompilerPass(new HttpCacheConfigCompilerPass());

        if ($container->getParameter('kernel.environment') === 'test') {
            $container->addCompilerPass(new DisableRateLimiterCompilerPass());
        }

        $container->addCompilerPass(new FrameworkMigrationReplacementCompilerPass());

        parent::build($container);
    }

    public function boot(): void
    {
        parent::boot();

        \assert($this->container instanceof ContainerInterface, 'Container is not set yet, please call setContainer() before calling boot(), see `src/Core/Kernel.php:186`.');

        $featureFlags = $this->container->getParameter('snap.feature.flags');
        if (!\is_array($featureFlags)) {
            throw new \RuntimeException('Container parameter "snap.feature.flags" needs to be an array');
        }
        Feature::registerFeatures($featureFlags);

        $cacheDir = $this->container->getParameter('kernel.cache_dir');
        if (!\is_string($cacheDir)) {
            throw new \RuntimeException('Container parameter "kernel.cache_dir" needs to be a string');
        }

        $this->registerEntityExtensions(
            $this->container->get(DefinitionInstanceRegistry::class),
            $this->container->get(ExtensionRegistry::class)
        );

        \assert($this->container instanceof ContainerInterface, 'Container is not set yet, please call setContainer() before calling boot(), see `src/Core/Kernel.php:186`.');

        CacheValueCompressor::$compress = $this->container->getParameter('snap.cache.cache_compression');
    }

    /**
     * @return string[]
     */
    protected function getCoreMigrationPaths(): array
    {
        return [
            __DIR__ . '/../Migration' => 'SnapAdmin\Core\Migration',
        ];
    }

    private function buildConfig(ContainerBuilder $container, string $environment): void
    {
        $cacheDir = $container->getParameter('kernel.cache_dir');
        if (!\is_string($cacheDir)) {
            throw new \RuntimeException('Container parameter "kernel.cache_dir" needs to be a string');
        }

        $locator = new FileLocator('Resources/config');

        $resolver = new LoaderResolver([
            new XmlFileLoader($container, $locator),
            new YamlFileLoader($container, $locator),
            new IniFileLoader($container, $locator),
            new PhpFileLoader($container, $locator),
            new GlobFileLoader($container, $locator),
            new DirectoryLoader($container, $locator),
            new ClosureLoader($container),
        ]);

        $configLoader = new DelegatingLoader($resolver);

        $confDir = $this->getPath() . '/Resources/config';

        $configLoader->load($confDir . '/{packages}/*' . Kernel::CONFIG_EXTS, 'glob');
        $configLoader->load($confDir . '/{packages}/' . $environment . '/*' . Kernel::CONFIG_EXTS, 'glob');
        if ($environment === 'e2e') {
            $configLoader->load($confDir . '/{packages}/prod/*' . Kernel::CONFIG_EXTS, 'glob');
        }
    }

    private function registerEntityExtensions(
        DefinitionInstanceRegistry             $definitionRegistry,
        ExtensionRegistry                      $registry
    ): void
    {
        foreach ($registry->getExtensions() as $extension) {
            /** @var string $class */
            $class = $extension->getDefinitionClass();

            $definition = $definitionRegistry->get($class);

            $definition->addExtension($extension);
        }
    }
}
