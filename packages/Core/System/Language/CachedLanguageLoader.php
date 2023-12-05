<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Language;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @internal
 *
 * @phpstan-import-type LanguageData from LanguageLoaderInterface
 */
#[Package('core')]
class CachedLanguageLoader implements LanguageLoaderInterface, EventSubscriberInterface
{
    private const CACHE_KEY = 'snap.languages';

    /**
     * @internal
     */
    public function __construct(
        private readonly LanguageLoaderInterface $loader,
        private readonly CacheInterface $cache
    ) {
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            LanguageEvents::LANGUAGE_DELETED_EVENT => 'invalidateCache',
            LanguageEvents::LANGUAGE_WRITTEN_EVENT => 'invalidateCache',
        ];
    }

    /**
     * @return LanguageData
     */
    public function loadLanguages(): array
    {
        return $this->cache->get(self::CACHE_KEY, fn () => $this->loader->loadLanguages());
    }

    public function invalidateCache(): void
    {
        $this->cache->delete(self::CACHE_KEY);
    }
}
