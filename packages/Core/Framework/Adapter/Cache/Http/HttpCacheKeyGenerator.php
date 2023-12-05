<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache\Http;

use SnapAdmin\Core\Framework\Adapter\Cache\Event\HttpCacheKeyEvent;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @internal
 */
#[Package('core')]
class HttpCacheKeyGenerator
{
    final public const CONTEXT_CACHE_COOKIE = 'sw-cache-hash';
    final public const SYSTEM_STATE_COOKIE = 'sw-states';
    final public const INVALIDATION_STATES_HEADER = 'sw-invalidation-states';

    /**
     * @param string[] $ignoredParameters
     *
     * @internal
     */
    public function __construct(
        private readonly string                   $cacheHash,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly array                    $ignoredParameters
    )
    {
    }

    /**
     * Generates a cache key for the given request.
     * This method should return a key that must only depend on a
     * normalized version of the request URI.
     * If the same URI can have more than one representation, based on some
     * headers, use a `vary` header to indicate them, and each representation will
     * be stored independently under the same cache key.
     *
     * @return string A key for the given request
     */
    public function generate(Request $request): string
    {
        $event = new HttpCacheKeyEvent($request);

        $event->add('uri', $this->getRequestUri($request));

        $event->add('hash', $this->cacheHash);

        $this->addCookies($request, $event);

        $this->dispatcher->dispatch($event);

        $parts = $event->getParts();

        return 'http-cache-' . hash('sha256', implode('|', $parts));
    }

    private function getRequestUri(Request $request): string
    {
        $params = $request->query->all();
        foreach (array_keys($params) as $key) {
            if (\in_array($key, $this->ignoredParameters, true)) {
                unset($params[$key]);
            }
        }
        ksort($params);
        $params = http_build_query($params);

        return sprintf(
            '%s%s%s%s',
            $request->getSchemeAndHttpHost(),
            '',
            $request->getPathInfo(),
            '?' . $params
        );
    }

    private function addCookies(Request $request, HttpCacheKeyEvent $event): void
    {
        // this will be changed within v6.6 lane that we only use the context cache cookie and developers can change the cookie instead
        // with this change, the reverse proxies are much easier to configure
        if ($request->cookies->has(self::CONTEXT_CACHE_COOKIE)) {
            $event->add(
                self::CONTEXT_CACHE_COOKIE,
                $request->cookies->get(self::CONTEXT_CACHE_COOKIE)
            );

            return;
        }

    }
}
