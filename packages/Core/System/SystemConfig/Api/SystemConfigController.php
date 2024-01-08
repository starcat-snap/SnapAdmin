<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Api;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Routing\RoutingException;
use SnapAdmin\Core\System\SystemConfig\Service\ConfigurationService;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;
use SnapAdmin\Core\System\SystemConfig\Validation\SystemConfigValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
#[Package('services-settings')]
class SystemConfigController extends AbstractController
{
    /**
     * @internal
     */
    public function __construct(
        private readonly ConfigurationService  $configurationService,
        private readonly SystemConfigService   $systemConfig,
        private readonly SystemConfigValidator $systemConfigValidator
    )
    {
    }

    #[Route(path: '/api/_action/system-config/check', name: 'api.action.core.system-config.check', defaults: ['_acl' => ['system_config:read']], methods: ['GET'])]
    public function checkConfiguration(Request $request, Context $context): JsonResponse
    {
        $domain = (string)$request->query->get('domain');

        if ($domain === '') {
            return new JsonResponse(false);
        }

        return new JsonResponse($this->configurationService->checkConfiguration($domain, $context));
    }

    #[Route(path: '/api/_action/system-config/schema', name: 'api.action.core.system-config', methods: ['GET'])]
    public function getConfiguration(Request $request, Context $context): JsonResponse
    {
        $domain = (string)$request->query->get('domain');

        if ($domain === '') {
            throw RoutingException::missingRequestParameter('domain');
        }

        return new JsonResponse($this->configurationService->getConfiguration($domain, $context));
    }

    #[Route(path: '/api/_action/system-config', name: 'api.action.core.system-config.value', defaults: ['_acl' => ['system_config:read']], methods: ['GET'])]
    public function getConfigurationValues(Request $request): JsonResponse
    {
        $domain = (string)$request->query->get('domain');
        if ($domain === '') {
            throw RoutingException::missingRequestParameter('domain');
        }

        $scopeId = $request->query->get('scopeId');
        $scope = $request->query->get('scope');
        if (!\is_string($scopeId)) {
            $scopeId = null;
        }
        if (!\is_string($scope)) {
            $scope = null;
        }
        $inherit = $request->query->getBoolean('inherit');

        $values = $this->systemConfig->getDomain($domain, $scopeId, $scope, $inherit);
        if (empty($values)) {
            $json = '{}';
        } else {
            $json = json_encode($values, \JSON_PRESERVE_ZERO_FRACTION);
        }

        return new JsonResponse($json, 200, [], true);
    }

    #[Route(path: '/api/_action/system-config', name: 'api.action.core.save.system-config', defaults: ['_acl' => ['system_config:update', 'system_config:create', 'system_config:delete']], methods: ['POST'])]
    public function saveConfiguration(Request $request): JsonResponse
    {
        $scopeId = $request->query->get('scopeId');
        $scope = $request->query->get('scope');
        if (!\is_string($scopeId)) {
            $scopeId = null;
        }
        if (!\is_string($scope)) {
            $scope = null;
        }
        $kvs = $request->request->all();
        $this->systemConfig->setMultiple($kvs, $scopeId, $scope);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/api/_action/system-config/batch', name: 'api.action.core.save.system-config.batch', defaults: ['_acl' => ['system_config:update', 'system_config:create', 'system_config:delete']], methods: ['POST'])]
    public function batchSaveConfiguration(Request $request, Context $context): JsonResponse
    {
        $this->systemConfigValidator->validate($request->request->all(), $context);

        /**
         * @var string $scope
         * @var array<string, mixed> $kv
         */
        foreach ($request->request->all() as $scope => $kv) {
            if ($scope === 'null') {
                $scope = null;
            }
            foreach ($kv as $scopeId => $kvs) {
                if ($scopeId === 'null') {
                    $scopeId = null;
                }
                $this->systemConfig->setMultiple($kvs,$scopeId, $scope);
            }
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
