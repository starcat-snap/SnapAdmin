<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Kernel;

use Composer\Autoload\ClassLoader;
use Composer\InstalledVersions;
use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Adapter\Cache\CacheIdLoader;
use SnapAdmin\Core\Framework\Adapter\Database\MySQLFactory;
use SnapAdmin\Core\Framework\Adapter\Storage\MySQLKeyValueStorage;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\KernelPluginLoader\DbalKernelPluginLoader;
use SnapAdmin\Core\Framework\Plugin\KernelPluginLoader\KernelPluginLoader;
use SnapAdmin\Core\Kernel;
use SnapAdmin\Core\Profiling\Doctrine\ProfilingMiddleware;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @final
 */
#[Package('core')]
class KernelFactory
{
    /**
     * @var class-string<Kernel>
     */
    public static string $kernelClass = Kernel::class;

    public static function create(
        string              $environment,
        bool                $debug,
        ClassLoader         $classLoader,
        ?KernelPluginLoader $pluginLoader = null,
        ?Connection         $connection = null
    ): HttpKernelInterface
    {
        if (InstalledVersions::isInstalled('snapadmin/platform')) {
            $snapVersion = InstalledVersions::getVersion('snapadmin/platform')
                . '@' . InstalledVersions::getReference('snapadmin/platform');
        } else {
            $snapVersion = InstalledVersions::getVersion('snapadmin/core')
                . '@' . InstalledVersions::getReference('snapadmin/core');
        }

        $middlewares = [];
        if (\PHP_SAPI !== 'cli' && $environment !== 'prod' && InstalledVersions::isInstalled('symfony/doctrine-bridge')) {
            $middlewares = [new ProfilingMiddleware()];
        }

        $connection = $connection ?? MySQlFactory::create($middlewares);

        $pluginLoader = $pluginLoader ?? new DbalKernelPluginLoader($classLoader, null, $connection);

        $storage = new MySQLKeyValueStorage($connection);
        $cacheId = (new CacheIdLoader($storage))->load();

        /** @var KernelInterface $kernel */
        $kernel = new static::$kernelClass(
            $environment,
            $debug,
            $pluginLoader,
            $cacheId,
            $snapVersion,
            $connection,
            self::getProjectDir()
        );

        return $kernel;
    }

    private static function getProjectDir(): string
    {
        if ($dir = $_ENV['PROJECT_ROOT'] ?? $_SERVER['PROJECT_ROOT'] ?? false) {
            return $dir;
        }

        $r = new \ReflectionClass(self::class);

        /** @var string $dir */
        $dir = $r->getFileName();

        $dir = $rootDir = \dirname($dir);
        while (!file_exists($dir . '/vendor')) {
            if ($dir === \dirname($dir)) {
                return $rootDir;
            }
            $dir = \dirname($dir);
        }

        return $dir;
    }
}
