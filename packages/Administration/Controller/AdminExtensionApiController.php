<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Controller;

use SnapAdmin\Administration\Controller\Exception\AppByNameNotFoundException;
use SnapAdmin\Administration\Controller\Exception\MissingAppSecretException;
use SnapAdmin\Administration\Controller\Exception\MissingShopUrlException;
use SnapAdmin\Core\DevOps\Environment\EnvironmentHelper;
use SnapAdmin\Core\Framework\App\ActionButton\AppAction;
use SnapAdmin\Core\Framework\App\ActionButton\Executor;
use SnapAdmin\Core\Framework\App\AppEntity;
use SnapAdmin\Core\Framework\App\Hmac\QuerySigner;
use SnapAdmin\Core\Framework\App\Manifest\Exception\UnallowedHostException;
use SnapAdmin\Core\Framework\App\ShopId\ShopIdProvider;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\Framework\Validation\DataBag\RequestDataBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @internal Only to be used by the admin-extension-sdk.
 */
#[Route(defaults: ['_routeScope' => ['api']])]
#[Package('administration')]
class AdminExtensionApiController extends AbstractController
{
    public function __construct(
        private readonly Executor $executor,
        private readonly ShopIdProvider $shopIdProvider,
        private readonly EntityRepository $appRepository,
        private readonly QuerySigner $querySigner
    ) {
    }

    #[Route(path: '/api/_action/extension-sdk/run-action', name: 'api.action.extension-sdk.run-action', methods: ['POST'])]
    public function runAction(RequestDataBag $requestDataBag, Context $context): Response
    {
        $appName = $requestDataBag->get('appName');
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('name', $appName)
        );

        /** @var AppEntity|null $app */
        $app = $this->appRepository->search($criteria, $context)->first();
        if ($app === null) {
            throw new AppByNameNotFoundException($appName);
        }

        $shopUrl = EnvironmentHelper::getVariable('APP_URL');
        if (!\is_string($shopUrl)) {
            throw new MissingShopUrlException();
        }

        $appSecret = $app->getAppSecret();
        if ($appSecret === null) {
            throw new MissingAppSecretException();
        }

        $targetUrl = $requestDataBag->get('url');
        $targetHost = \parse_url((string) $targetUrl, \PHP_URL_HOST);
        $allowedHosts = $app->getAllowedHosts() ?? [];
        if (!$targetHost || !\in_array($targetHost, $allowedHosts, true)) {
            throw new UnallowedHostException($targetUrl, $allowedHosts, $app->getName());
        }

        $action = new AppAction(
            $targetUrl,
            $shopUrl,
            $app->getVersion(),
            $requestDataBag->get('entity'),
            $requestDataBag->get('action'),
            $requestDataBag->get('ids')->all(),
            $appSecret,
            $this->shopIdProvider->getShopId(),
            Uuid::randomHex()
        );

        return $this->executor->execute($action, $context);
    }

    #[Route(path: '/api/_action/extension-sdk/sign-uri', name: 'api.action.extension-sdk.sign-uri', methods: ['POST'])]
    public function signUri(RequestDataBag $requestDataBag, Context $context): Response
    {
        $appName = $requestDataBag->get('appName');
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('name', $appName)
        );

        /** @var AppEntity|null $app */
        $app = $this->appRepository->search($criteria, $context)->first();
        if ($app === null) {
            throw new AppByNameNotFoundException($appName);
        }

        $secret = $app->getAppSecret();
        if ($secret === null) {
            throw new MissingAppSecretException();
        }

        $uri = $this->querySigner->signUri($requestDataBag->get('uri'), $secret, $context)->__toString();

        return new JsonResponse([
            'uri' => $uri,
        ]);
    }
}
