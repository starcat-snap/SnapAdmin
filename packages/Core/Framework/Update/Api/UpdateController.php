<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Update\Api;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\KernelPluginLoader\StaticKernelPluginLoader;
use SnapAdmin\Core\Framework\Store\Services\AbstractExtensionLifecycle;
use SnapAdmin\Core\Framework\Update\Checkers\LicenseCheck;
use SnapAdmin\Core\Framework\Update\Checkers\WriteableCheck;
use SnapAdmin\Core\Framework\Update\Event\UpdatePostPrepareEvent;
use SnapAdmin\Core\Framework\Update\Event\UpdatePrePrepareEvent;
use SnapAdmin\Core\Framework\Update\Services\ApiClient;
use SnapAdmin\Core\Framework\Update\Services\ExtensionCompatibility;
use SnapAdmin\Core\Framework\Update\Steps\DeactivateExtensionsStep;
use SnapAdmin\Core\Kernel;
use SnapAdmin\Core\System\SalesChannel\NoContentResponse;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @internal
 */
#[Route(defaults: ['_routeScope' => ['api']])]
#[Package('system-settings')]
class UpdateController extends AbstractController
{
    public const UPDATE_PREVIOUS_VERSION_KEY = 'core.update.previousVersion';

    /**
     * @internal
     */
    public function __construct(
        private readonly ApiClient $apiClient,
        private readonly WriteableCheck $writeableCheck,
        private readonly LicenseCheck $licenseCheck,
        private readonly ExtensionCompatibility $extensionCompatibility,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly SystemConfigService $systemConfig,
        private readonly AbstractExtensionLifecycle $extensionLifecycleService,
        private readonly string $snapVersion,
        private readonly bool $disableUpdateCheck = false
    ) {
    }

    #[Route(path: '/api/_action/update/check', name: 'api.custom.updateapi.check', defaults: ['_acl' => ['system:core:update']], methods: ['GET'])]
    public function updateApiCheck(): JsonResponse
    {
        if ($this->disableUpdateCheck) {
            return new JsonResponse();
        }

        $updates = $this->apiClient->checkForUpdates();

        if (version_compare($this->snapVersion, $updates->version, '>=')) {
            return new JsonResponse();
        }

        return new JsonResponse($updates);
    }

    #[Route(path: '/api/_action/update/check-requirements', name: 'api.custom.update.check_requirements', defaults: ['_acl' => ['system:core:update']], methods: ['GET'])]
    public function checkRequirements(): JsonResponse
    {
        return new JsonResponse([
            $this->writeableCheck->check(),
            $this->licenseCheck->check(),
        ]);
    }

    #[Route('/api/_action/update/extension-compatibility', name: 'api.custom.updateapi.extension_compatibility', defaults: ['_acl' => ['system:core:update', 'system_config:read']], methods: ['GET'])]
    public function extensionCompatibility(Context $context): JsonResponse
    {
        $update = $this->apiClient->checkForUpdates();

        return new JsonResponse($this->extensionCompatibility->getExtensionCompatibilities($update, $context));
    }

    #[Route(path: '/api/_action/update/download-recovery', name: 'api.custom.updateapi.download-recovery', defaults: ['_acl' => ['system:core:update', 'system_config:read']], methods: ['GET'])]
    public function downloadLatestRecovery(): Response
    {
        $this->apiClient->downloadRecoveryTool();

        return new NoContentResponse();
    }

    #[Route(path: '/api/_action/update/deactivate-plugins', name: 'api.custom.updateapi.deactivate-plugins', defaults: ['_acl' => ['system:core:update', 'system_config:read']], methods: ['GET'])]
    public function deactivatePlugins(Request $request, Context $context): JsonResponse
    {
        $update = $this->apiClient->checkForUpdates();

        $offset = $request->query->getInt('offset');

        if ($offset === 0) {
            // plugins can subscribe to these events, check compatibility and throw exceptions to prevent the update
            $this->eventDispatcher->dispatch(
                new UpdatePrePrepareEvent($context, $this->snapVersion, $update->version)
            );
        }

        // disable plugins - save active plugins
        $deactivationFilter = (string) $request->query->get(
            'deactivationFilter',
            ExtensionCompatibility::PLUGIN_DEACTIVATION_FILTER_NOT_COMPATIBLE
        );

        $deactivatePluginStep = new DeactivateExtensionsStep(
            $update,
            $deactivationFilter,
            $this->extensionCompatibility,
            $this->extensionLifecycleService,
            $this->systemConfig,
            $context
        );

        $result = $deactivatePluginStep->run($offset);

        if ($result->getOffset() === $result->getTotal()) {
            $containerWithoutPlugins = $this->rebootKernelWithoutPlugins();

            // @internal plugins are deactivated
            $containerWithoutPlugins->get('event_dispatcher')->dispatch(
                new UpdatePostPrepareEvent($context, $this->snapVersion, $update->version)
            );
        }

        return new JsonResponse([
            'offset' => $result->getOffset(),
            'total' => $result->getTotal(),
        ]);
    }

    private function rebootKernelWithoutPlugins(): ContainerInterface
    {
        /** @var Kernel $kernel */
        $kernel = $this->container->get('kernel');

        $classLoad = $kernel->getPluginLoader()->getClassLoader();
        $kernel->reboot(null, new StaticKernelPluginLoader($classLoad));

        return $kernel->getContainer();
    }
}
