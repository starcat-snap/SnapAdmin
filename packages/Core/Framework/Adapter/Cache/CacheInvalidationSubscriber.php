<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SystemConfig\CachedSystemConfigLoader;
use SnapAdmin\Core\System\SystemConfig\Event\SystemConfigChangedHook;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;

#[Package('core')]
class CacheInvalidationSubscriber
{
    /**
     * @internal
     */
    public function __construct(
        private readonly CacheInvalidator $cacheInvalidator,
        private readonly Connection       $connection,
        private readonly bool             $fineGrainedCacheSnippet,
        private readonly bool             $fineGrainedCacheConfig
    )
    {
    }

    public function invalidateConfig(): void
    {
        // invalidates the complete cached config
        $this->cacheInvalidator->invalidate([
            CachedSystemConfigLoader::CACHE_TAG,
        ]);
    }

    public function invalidateConfigKey(SystemConfigChangedHook $event): void
    {
        $keys = [];

        if ($this->fineGrainedCacheConfig) {
            /** @var list<string> $keys */
            $keys = array_map(
                static fn(string $key) => SystemConfigService::buildName($key),
                $event->getWebhookPayload()['changes']
            );
        } else {
            $keys[] = 'global.system.config';
        }

        // invalidates the complete cached config and routes which access a specific key
        $this->cacheInvalidator->invalidate([
            ...$keys,
            CachedSystemConfigLoader::CACHE_TAG,
        ]);
    }
}
