<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SystemConfig\CachedSystemConfigLoader;
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
}
