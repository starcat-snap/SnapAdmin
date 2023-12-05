<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin;

use Composer\InstalledVersions;
use Composer\IO\NullIO;
use Composer\Semver\Comparator;
use Psr\Cache\CacheItemPoolInterface;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Api\Context\SystemSource;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Migration\MigrationCollection;
use SnapAdmin\Core\Framework\Migration\MigrationCollectionLoader;
use SnapAdmin\Core\Framework\Migration\MigrationSource;
use SnapAdmin\Core\Framework\Plugin;
use SnapAdmin\Core\Framework\Plugin\Composer\CommandExecutor;
use SnapAdmin\Core\Framework\Plugin\Context\ActivateContext;
use SnapAdmin\Core\Framework\Plugin\Context\DeactivateContext;
use SnapAdmin\Core\Framework\Plugin\Context\InstallContext;
use SnapAdmin\Core\Framework\Plugin\Context\UninstallContext;
use SnapAdmin\Core\Framework\Plugin\Context\UpdateContext;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostActivateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostDeactivateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostDeactivationFailedEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostInstallEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostUninstallEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostUpdateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPreActivateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPreDeactivateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPreInstallEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPreUninstallEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPreUpdateEvent;
use SnapAdmin\Core\Framework\Plugin\Exception\PluginBaseClassNotFoundException;
use SnapAdmin\Core\Framework\Plugin\Exception\PluginComposerJsonInvalidException;
use SnapAdmin\Core\Framework\Plugin\Exception\PluginHasActiveDependantsException;
use SnapAdmin\Core\Framework\Plugin\Exception\PluginNotActivatedException;
use SnapAdmin\Core\Framework\Plugin\Exception\PluginNotInstalledException;
use SnapAdmin\Core\Framework\Plugin\KernelPluginLoader\KernelPluginLoader;
use SnapAdmin\Core\Framework\Plugin\KernelPluginLoader\StaticKernelPluginLoader;
use SnapAdmin\Core\Framework\Plugin\Requirement\Exception\RequirementStackException;
use SnapAdmin\Core\Framework\Plugin\Requirement\RequirementsValidator;
use SnapAdmin\Core\Framework\Plugin\Util\AssetService;
use SnapAdmin\Core\Framework\Plugin\Util\VersionSanitizer;
use SnapAdmin\Core\System\CustomEntity\CustomEntityLifecycleService;
use SnapAdmin\Core\System\CustomEntity\Schema\CustomEntityPersister;
use SnapAdmin\Core\System\CustomEntity\Schema\CustomEntitySchemaUpdater;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\EventListener\StopWorkerOnRestartSignalListener;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @internal
 */
#[Package('core')]
class PluginLifecycleService
{
    final public const STATE_SKIP_ASSET_BUILDING = 'skip-asset-building';

    /**
     * @param EntityRepository<PluginCollection> $pluginRepo
     */
    public function __construct(
        private readonly EntityRepository             $pluginRepo,
        private EventDispatcherInterface              $eventDispatcher,
        private readonly KernelPluginCollection       $pluginCollection,
        private ContainerInterface                    $container,
        private readonly MigrationCollectionLoader    $migrationLoader,
        private readonly AssetService                 $assetInstaller,
        private readonly CommandExecutor              $executor,
        private readonly RequirementsValidator        $requirementValidator,
        private readonly CacheItemPoolInterface       $restartSignalCachePool,
        private readonly string                       $snapVersion,
        private readonly SystemConfigService          $systemConfigService,
        private readonly CustomEntityPersister        $customEntityPersister,
        private readonly CustomEntitySchemaUpdater    $customEntitySchemaUpdater,
        private readonly CustomEntityLifecycleService $customEntityLifecycleService,
        private readonly PluginService                $pluginService,
        private readonly VersionSanitizer             $versionSanitizer,
    )
    {
    }

    /**
     * @throws RequirementStackException
     */
    public function installPlugin(PluginEntity $plugin, Context $snapContext): InstallContext
    {
        $pluginData = [];
        $pluginBaseClass = $this->getPluginBaseClass($plugin->getBaseClass());
        $pluginVersion = $plugin->getVersion();

        $installContext = new InstallContext(
            $pluginBaseClass,
            $snapContext,
            $this->snapVersion,
            $pluginVersion,
            $this->createMigrationCollection($pluginBaseClass)
        );

        if ($plugin->getInstalledAt()) {
            return $installContext;
        }

        $didRunComposerRequire = false;

        if ($pluginBaseClass->executeComposerCommands()) {
            $didRunComposerRequire = $this->executeComposerRequireWhenNeeded($plugin, $pluginBaseClass, $pluginVersion, $snapContext);
        } else {
            $this->requirementValidator->validateRequirements($plugin, $snapContext, 'install');
        }

        try {
            $pluginData['id'] = $plugin->getId();

            // Makes sure the version is updated in the db after a re-installation
            $updateVersion = $plugin->getUpgradeVersion();
            if ($updateVersion !== null && $this->hasPluginUpdate($updateVersion, $pluginVersion)) {
                $pluginData['version'] = $updateVersion;
                $plugin->setVersion($updateVersion);
                $pluginData['upgradeVersion'] = null;
                $plugin->setUpgradeVersion(null);
                $upgradeDate = new \DateTime();
                $pluginData['upgradedAt'] = $upgradeDate->format(Defaults::STORAGE_DATE_TIME_FORMAT);
                $plugin->setUpgradedAt($upgradeDate);
            }

            $this->eventDispatcher->dispatch(new PluginPreInstallEvent($plugin, $installContext));

            $this->systemConfigService->savePluginConfiguration($pluginBaseClass, true);

            $pluginBaseClass->install($installContext);

            $this->customEntityLifecycleService->updatePlugin($plugin->getId(), $plugin->getPath() ?? '');

            $this->runMigrations($installContext);

            $installDate = new \DateTime();
            $pluginData['installedAt'] = $installDate->format(Defaults::STORAGE_DATE_TIME_FORMAT);
            $plugin->setInstalledAt($installDate);

            $this->updatePluginData($pluginData, $snapContext);

            $pluginBaseClass->postInstall($installContext);

            $this->eventDispatcher->dispatch(new PluginPostInstallEvent($plugin, $installContext));
        } catch (\Throwable $e) {
            if ($didRunComposerRequire && $plugin->getComposerName()) {
                $this->executor->remove($plugin->getComposerName(), $plugin->getName());
            }

            throw $e;
        }

        return $installContext;
    }

    /**
     * @throws PluginNotInstalledException
     */
    public function uninstallPlugin(
        PluginEntity $plugin,
        Context      $snapContext,
        bool         $keepUserData = false
    ): UninstallContext
    {
        if ($plugin->getInstalledAt() === null) {
            throw new PluginNotInstalledException($plugin->getName());
        }

        if ($plugin->getActive()) {
            $this->deactivatePlugin($plugin, $snapContext);
        }

        $pluginBaseClassString = $plugin->getBaseClass();
        $pluginBaseClass = $this->getPluginBaseClass($pluginBaseClassString);

        $uninstallContext = new UninstallContext(
            $pluginBaseClass,
            $snapContext,
            $this->snapVersion,
            $plugin->getVersion(),
            $this->createMigrationCollection($pluginBaseClass),
            $keepUserData
        );
        $uninstallContext->setAutoMigrate(false);

        $this->eventDispatcher->dispatch(new PluginPreUninstallEvent($plugin, $uninstallContext));

        if (!$snapContext->hasState(self::STATE_SKIP_ASSET_BUILDING)) {
            $this->assetInstaller->removeAssetsOfBundle($pluginBaseClassString);
        }

        $pluginBaseClass->uninstall($uninstallContext);

        if (!$uninstallContext->keepUserData()) {
            $pluginBaseClass->removeMigrations();
            $this->systemConfigService->deletePluginConfiguration($pluginBaseClass);
        }

        $pluginId = $plugin->getId();
        $this->updatePluginData(
            [
                'id' => $pluginId,
                'active' => false,
                'installedAt' => null,
            ],
            $snapContext
        );
        $plugin->setActive(false);
        $plugin->setInstalledAt(null);

        if (!$uninstallContext->keepUserData()) {
            $this->removeCustomEntities($plugin->getId());
        }

        if ($pluginBaseClass->executeComposerCommands()) {
            $pluginComposerName = $plugin->getComposerName();
            if ($pluginComposerName === null) {
                throw new PluginComposerJsonInvalidException(
                    $pluginBaseClass->getPath() . '/composer.json',
                    ['No name defined in composer.json']
                );
            }
            $this->executor->remove($pluginComposerName, $plugin->getName());

            // running composer require may have consequences for other plugins, when they are required by the plugin being uninstalled
            $this->pluginService->refreshPlugins($snapContext, new NullIO());
        }

        $this->eventDispatcher->dispatch(new PluginPostUninstallEvent($plugin, $uninstallContext));

        return $uninstallContext;
    }

    /**
     * @throws RequirementStackException
     */
    public function updatePlugin(PluginEntity $plugin, Context $snapContext): UpdateContext
    {
        if ($plugin->getInstalledAt() === null) {
            throw new PluginNotInstalledException($plugin->getName());
        }

        $pluginBaseClassString = $plugin->getBaseClass();
        $pluginBaseClass = $this->getPluginBaseClass($pluginBaseClassString);

        $updateContext = new UpdateContext(
            $pluginBaseClass,
            $snapContext,
            $this->snapVersion,
            $plugin->getVersion(),
            $this->createMigrationCollection($pluginBaseClass),
            $plugin->getUpgradeVersion() ?? $plugin->getVersion()
        );

        if ($pluginBaseClass->executeComposerCommands()) {
            $this->executeComposerRequireWhenNeeded($plugin, $pluginBaseClass, $updateContext->getUpdatePluginVersion(), $snapContext);
        } else {
            $this->requirementValidator->validateRequirements($plugin, $snapContext, 'update');
        }

        $this->eventDispatcher->dispatch(new PluginPreUpdateEvent($plugin, $updateContext));

        $this->systemConfigService->savePluginConfiguration($pluginBaseClass);

        try {
            $pluginBaseClass->update($updateContext);
        } catch (\Throwable $updateException) {
            if ($plugin->getActive()) {
                try {
                    $this->deactivatePlugin($plugin, $snapContext);
                } catch (\Throwable) {
                    $this->updatePluginData(
                        [
                            'id' => $plugin->getId(),
                            'active' => false,
                        ],
                        $snapContext
                    );
                }
            }

            throw $updateException;
        }

        if ($plugin->getActive() && !$snapContext->hasState(self::STATE_SKIP_ASSET_BUILDING)) {
            $this->assetInstaller->copyAssetsFromBundle($pluginBaseClassString);
        }

        $this->customEntityLifecycleService->updatePlugin($plugin->getId(), $plugin->getPath() ?? '');
        $this->runMigrations($updateContext);

        $updateVersion = $updateContext->getUpdatePluginVersion();
        $updateDate = new \DateTime();
        $this->updatePluginData(
            [
                'id' => $plugin->getId(),
                'version' => $updateVersion,
                'upgradeVersion' => null,
                'upgradedAt' => $updateDate->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ],
            $snapContext
        );
        $plugin->setVersion($updateVersion);
        $plugin->setUpgradeVersion(null);
        $plugin->setUpgradedAt($updateDate);

        $pluginBaseClass->postUpdate($updateContext);

        $this->eventDispatcher->dispatch(new PluginPostUpdateEvent($plugin, $updateContext));

        return $updateContext;
    }

    /**
     * @throws PluginNotInstalledException
     */
    public function activatePlugin(PluginEntity $plugin, Context $snapContext, bool $reactivate = false): ActivateContext
    {
        if ($plugin->getInstalledAt() === null) {
            throw new PluginNotInstalledException($plugin->getName());
        }

        $pluginBaseClassString = $plugin->getBaseClass();
        $pluginBaseClass = $this->getPluginBaseClass($pluginBaseClassString);

        $activateContext = new ActivateContext(
            $pluginBaseClass,
            $snapContext,
            $this->snapVersion,
            $plugin->getVersion(),
            $this->createMigrationCollection($pluginBaseClass)
        );

        if ($reactivate === false && $plugin->getActive()) {
            return $activateContext;
        }

        $this->requirementValidator->validateRequirements($plugin, $snapContext, 'activate');

        $this->eventDispatcher->dispatch(new PluginPreActivateEvent($plugin, $activateContext));

        $plugin->setActive(true);

        // only skip rebuild if plugin has overwritten rebuildContainer method and source is system source (CLI)
        if ($pluginBaseClass->rebuildContainer() || !$snapContext->getSource() instanceof SystemSource) {
            $this->rebuildContainerWithNewPluginState($plugin);
        }

        $pluginBaseClass = $this->getPluginInstance($pluginBaseClassString);
        $activateContext = new ActivateContext(
            $pluginBaseClass,
            $snapContext,
            $this->snapVersion,
            $plugin->getVersion(),
            $this->createMigrationCollection($pluginBaseClass)
        );
        $activateContext->setAutoMigrate(false);

        $pluginBaseClass->activate($activateContext);

        $this->runMigrations($activateContext);

        if (!$snapContext->hasState(self::STATE_SKIP_ASSET_BUILDING)) {
            $this->assetInstaller->copyAssetsFromBundle($pluginBaseClassString);
        }

        $this->updatePluginData(
            [
                'id' => $plugin->getId(),
                'active' => true,
            ],
            $snapContext
        );

        $this->signalWorkerStopInOldCacheDir();

        $this->eventDispatcher->dispatch(new PluginPostActivateEvent($plugin, $activateContext));

        return $activateContext;
    }

    /**
     * @throws PluginNotInstalledException
     * @throws PluginNotActivatedException
     * @throws PluginHasActiveDependantsException
     */
    public function deactivatePlugin(PluginEntity $plugin, Context $snapContext): DeactivateContext
    {
        if ($plugin->getInstalledAt() === null) {
            throw new PluginNotInstalledException($plugin->getName());
        }

        if ($plugin->getActive() === false) {
            throw new PluginNotActivatedException($plugin->getName());
        }

        $dependantPlugins = $this->getEntities($this->pluginCollection->all(), $snapContext)->getEntities()->getElements();

        $dependants = $this->requirementValidator->resolveActiveDependants(
            $plugin,
            $dependantPlugins
        );

        if (\count($dependants) > 0) {
            throw new PluginHasActiveDependantsException($plugin->getName(), $dependants);
        }

        $pluginBaseClassString = $plugin->getBaseClass();
        $pluginBaseClass = $this->getPluginInstance($pluginBaseClassString);

        $deactivateContext = new DeactivateContext(
            $pluginBaseClass,
            $snapContext,
            $this->snapVersion,
            $plugin->getVersion(),
            $this->createMigrationCollection($pluginBaseClass)
        );
        $deactivateContext->setAutoMigrate(false);

        $this->eventDispatcher->dispatch(new PluginPreDeactivateEvent($plugin, $deactivateContext));

        try {
            $pluginBaseClass->deactivate($deactivateContext);

            if (!$snapContext->hasState(self::STATE_SKIP_ASSET_BUILDING)) {
                $this->assetInstaller->removeAssetsOfBundle($plugin->getName());
            }

            $plugin->setActive(false);

            // only skip rebuild if plugin has overwritten rebuildContainer method and source is system source (CLI)
            if ($pluginBaseClass->rebuildContainer() || !$snapContext->getSource() instanceof SystemSource) {
                $this->rebuildContainerWithNewPluginState($plugin);
            }

            $this->updatePluginData(
                [
                    'id' => $plugin->getId(),
                    'active' => false,
                ],
                $snapContext
            );
        } catch (\Throwable $exception) {
            $activateContext = new ActivateContext(
                $pluginBaseClass,
                $snapContext,
                $this->snapVersion,
                $plugin->getVersion(),
                $this->createMigrationCollection($pluginBaseClass)
            );

            $this->eventDispatcher->dispatch(
                new PluginPostDeactivationFailedEvent(
                    $plugin,
                    $activateContext,
                    $exception
                )
            );

            throw $exception;
        }

        $this->signalWorkerStopInOldCacheDir();

        $this->eventDispatcher->dispatch(new PluginPostDeactivateEvent($plugin, $deactivateContext));

        return $deactivateContext;
    }

    private function removeCustomEntities(string $pluginId): void
    {
        $this->customEntityPersister->update([], PluginEntity::class, $pluginId);
        $this->customEntitySchemaUpdater->update();
    }

    private function getPluginBaseClass(string $pluginBaseClassString): Plugin
    {
        $baseClass = $this->pluginCollection->get($pluginBaseClassString);

        if ($baseClass === null) {
            throw new PluginBaseClassNotFoundException($pluginBaseClassString);
        }

        // set container because the plugin has not been initialized yet and therefore has no container set
        $baseClass->setContainer($this->container);

        return $baseClass;
    }

    private function createMigrationCollection(Plugin $pluginBaseClass): MigrationCollection
    {
        $migrationPath = str_replace(
            '\\',
            '/',
            $pluginBaseClass->getPath() . str_replace(
                $pluginBaseClass->getNamespace(),
                '',
                $pluginBaseClass->getMigrationNamespace()
            )
        );

        if (!is_dir($migrationPath)) {
            return $this->migrationLoader->collect('null');
        }

        $this->migrationLoader->addSource(new MigrationSource($pluginBaseClass->getName(), [
            $migrationPath => $pluginBaseClass->getMigrationNamespace(),
        ]));

        $collection = $this->migrationLoader
            ->collect($pluginBaseClass->getName());

        $collection->sync();

        return $collection;
    }

    private function runMigrations(InstallContext $context): void
    {
        if (!$context->isAutoMigrate()) {
            return;
        }

        $context->getMigrationCollection()->migrateInPlace();
    }

    private function hasPluginUpdate(string $updateVersion, string $currentVersion): bool
    {
        return version_compare($updateVersion, $currentVersion, '>');
    }

    /**
     * @param array<string, mixed|null> $pluginData
     */
    private function updatePluginData(array $pluginData, Context $context): void
    {
        $this->pluginRepo->update([$pluginData], $context);
    }

    private function rebuildContainerWithNewPluginState(PluginEntity $plugin): void
    {
        $kernel = $this->container->get('kernel');

        $pluginDir = $kernel->getContainer()->getParameter('kernel.plugin_dir');
        if (!\is_string($pluginDir)) {
            throw new \RuntimeException('Container parameter "kernel.plugin_dir" needs to be a string');
        }

        $pluginLoader = $this->container->get(KernelPluginLoader::class);

        $plugins = $pluginLoader->getPluginInfos();
        foreach ($plugins as $i => $pluginData) {
            if ($pluginData['baseClass'] === $plugin->getBaseClass()) {
                $plugins[$i]['active'] = $plugin->getActive();
            }
        }

        /*
         * Reboot kernel with $plugin active=true.
         *
         * All other Requests won't have this plugin active until it's updated in the db
         */
        $tmpStaticPluginLoader = new StaticKernelPluginLoader($pluginLoader->getClassLoader(), $pluginDir, $plugins);
        $kernel->reboot(null, $tmpStaticPluginLoader);

        try {
            $newContainer = $kernel->getContainer();
        } catch (\LogicException) {
            // If symfony throws an exception when calling getContainer on a not booted kernel and catch it here
            throw new \RuntimeException('Failed to reboot the kernel');
        }

        $this->container = $newContainer;
        $this->eventDispatcher = $newContainer->get('event_dispatcher');
    }

    private function getPluginInstance(string $pluginBaseClassString): Plugin
    {
        if ($this->container->has($pluginBaseClassString)) {
            $containerPlugin = $this->container->get($pluginBaseClassString);
            if (!$containerPlugin instanceof Plugin) {
                throw new \RuntimeException($pluginBaseClassString . ' in the container should be an instance of ' . Plugin::class);
            }

            return $containerPlugin;
        }

        return $this->getPluginBaseClass($pluginBaseClassString);
    }

    private function signalWorkerStopInOldCacheDir(): void
    {
        $cacheItem = $this->restartSignalCachePool->getItem(StopWorkerOnRestartSignalListener::RESTART_REQUESTED_TIMESTAMP_KEY);
        $cacheItem->set(microtime(true));
        $this->restartSignalCachePool->save($cacheItem);
    }

    /**
     * Takes plugin base classes and returns the corresponding entities.
     *
     * @param Plugin[] $plugins
     *
     * @return EntitySearchResult<PluginCollection>
     */
    private function getEntities(array $plugins, Context $context): EntitySearchResult
    {
        $names = array_map(static fn(Plugin $plugin) => $plugin->getName(), $plugins);

        return $this->pluginRepo->search(
            (new Criteria())->addFilter(new EqualsAnyFilter('name', $names)),
            $context
        );
    }

    private function executeComposerRequireWhenNeeded(PluginEntity $plugin, Plugin $pluginBaseClass, string $pluginVersion, Context $snapContext): bool
    {
        $pluginComposerName = $plugin->getComposerName();
        if ($pluginComposerName === null) {
            throw new PluginComposerJsonInvalidException(
                $pluginBaseClass->getPath() . '/composer.json',
                ['No name defined in composer.json']
            );
        }

        try {
            $installedVersion = InstalledVersions::getVersion($pluginComposerName);
        } catch (\OutOfBoundsException) {
            // plugin is not installed using composer yet
            $installedVersion = null;
        }

        if ($installedVersion !== null) {
            $sanitizedVersion = $this->versionSanitizer->sanitizePluginVersion($installedVersion);

            if (Comparator::equalTo($sanitizedVersion, $pluginVersion)) {
                // plugin was already required at build time, no need to do so again at runtime
                return false;
            }
        }

        $this->executor->require($pluginComposerName . ':' . $pluginVersion, $plugin->getName());

        // running composer require may have consequences for other plugins, when they are required by the plugin being installed
        $this->pluginService->refreshPlugins($snapContext, new NullIO());

        return true;
    }
}
