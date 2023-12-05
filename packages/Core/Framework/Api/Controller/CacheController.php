<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Controller;

use SnapAdmin\Core\Framework\Adapter\Cache\CacheClearer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexerRegistry;
use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Util\Random;
use SnapAdmin\Core\Framework\Validation\DataBag\RequestDataBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\TraceableAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
#[Package('system-settings')]
class CacheController extends AbstractController
{
    /**
     * @internal
     */
    public function __construct(
        private readonly CacheClearer          $cacheClearer,
        private readonly AdapterInterface      $adapter,
        private readonly EntityIndexerRegistry $indexerRegistry
    )
    {
    }

    #[Route(path: '/api/_action/cache_info', name: 'api.action.cache.info', methods: ['GET'], defaults: ['_acl' => ['system:cache:info']])]
    public function info(): JsonResponse
    {
        return new JsonResponse([
            'environment' => $this->getParameter('kernel.environment'),
            'httpCache' => $this->container->get('parameter_bag')->has('snap.http.cache.enabled') && $this->getParameter('snap.http.cache.enabled'),
            'cacheAdapter' => $this->getUsedCache($this->adapter),
        ]);
    }

    #[Route(path: '/api/_action/index', name: 'api.action.cache.index', methods: ['POST'], defaults: ['_acl' => ['api_action_cache_index']])]
    public function index(RequestDataBag $dataBag): Response
    {
        $data = $dataBag->all();

        $skip = !empty($data['skip']) && \is_array($data['skip']) ? $data['skip'] : [];
        $mapped = [];
        foreach ($skip as $value) {
            $mapped[] = (string)$value;
        }

        $this->indexerRegistry->sendIndexingMessage([], $mapped);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/api/_action/cache_warmup', name: 'api.action.cache.delete_and_warmup', methods: ['DELETE'], defaults: ['_acl' => ['system:clear:cache']])]
    public function clearCacheAndScheduleWarmUp(): Response
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.6.0.0')
        );
        if ($this->cacheWarmer === null) {
            throw new \RuntimeException('Frontend is not installed');
        }

        $this->cacheWarmer->warmUp(Random::getAlphanumericString(32));

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/api/_action/cache', name: 'api.action.cache.delete', methods: ['DELETE'], defaults: ['_acl' => ['system:clear:cache']])]
    public function clearCache(): Response
    {
        $this->cacheClearer->clear();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/api/_action/cleanup', name: 'api.action.cache.cleanup', methods: ['DELETE'], defaults: ['_acl' => ['system:clear:cache']])]
    public function clearOldCacheFolders(): Response
    {
        $this->cacheClearer->scheduleCacheFolderCleanup();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/api/_action/container_cache', name: 'api.action.container-cache.delete', methods: ['DELETE'], defaults: ['_acl' => ['system:clear:cache']])]
    public function clearContainerCache(): Response
    {
        $this->cacheClearer->clearContainerCache();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function getUsedCache(AdapterInterface $adapter): string
    {
        if ($adapter instanceof TagAwareAdapter || $adapter instanceof TraceableAdapter) {
            // Do not declare function as static
            $func = \Closure::bind(fn() => $adapter->getPool(), $adapter, $adapter::class);

            $adapter = $func();
        }

        if ($adapter instanceof TraceableAdapter) {
            return $this->getUsedCache($adapter);
        }

        $name = $adapter::class;
        \assert(\is_string($name));
        $parts = explode('\\', $name);
        $name = str_replace('Adapter', '', end($parts));

        return $name;
    }
}
