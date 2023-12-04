<?php declare(strict_types=1);

namespace SnapAdmin\Core;

use SnapAdmin\Core\Framework\Util\VersionParser;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    use MicroKernelTrait;

    final public const CONFIG_EXT = '.{php,xml,yaml,yml}';

    protected string $snapAdminVersion;

    protected ?string $snapAdminVersionRevision;

    /**
     * @var string Fallback version if nothing is provided via kernel constructor
     */
    final public const SNAP_ADMIN_FALLBACK_VERSION = '1.0.9999999.9999999-dev';

    public function __construct(
        string  $environment,
        bool    $debug,
        ?string $version = self::SNAP_ADMIN_FALLBACK_VERSION
    )
    {
        $version = $version ?? self::SNAP_ADMIN_FALLBACK_VERSION;
        date_default_timezone_set('Asia/Shanghai');
        parent::__construct($environment, $debug);
        $version = VersionParser::parseSnapAdminVersion($version);
        $this->snapAdminVersion = $version['version'];
        $this->snapAdminVersionRevision = $version['revision'];
    }

    public function registerBundles(): iterable
    {
        /** @var array<class-string<Bundle>, array<string, bool>> $bundles */
        $bundles = require $this->getProjectDir() . '/config/bundles.php';
        $instantiatedBundleNames = [];

        foreach ($bundles as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->environment])) {
                $bundle = new $class();
                $instantiatedBundleNames[] = $bundle->getName();

                yield $bundle;
            }
        }
        return $instantiatedBundleNames;
    }

    protected function getKernelParameters(): array
    {
         $parameters = parent::getKernelParameters();

         return array_merge(
             $parameters,
             [
                   'kernel.snap_admin_version' => $this->snapAdminVersion,
             ]
         );
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->setParameter('.container.dumper.inline_class_loader', $this->environment !== 'test');
        $container->setParameter('.container.dumper.inline_factories', $this->environment !== 'test');

        $confDir = $this->getProjectDir() . '/config';

        $loader->load($confDir . '/{packages}/*' . self::CONFIG_EXT, 'glob');
        $loader->load($confDir . '/{packages}/' . $this->environment . '/**/*' . self::CONFIG_EXT, 'glob');
        $loader->load($confDir . '/{services}' . self::CONFIG_EXT, 'glob');
        $loader->load($confDir . '/{services}_' . $this->environment . self::CONFIG_EXT, 'glob');
    }
}
