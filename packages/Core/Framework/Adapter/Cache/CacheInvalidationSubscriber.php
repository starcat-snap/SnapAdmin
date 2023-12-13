<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SystemConfig\CachedSystemConfigLoader;

#[Package('core')]
class CacheInvalidationSubscriber
{
    /**
     * @internal
     */
    public function __construct(
        private readonly CacheInvalidator $cacheInvalidator
    ) {
    }

    public function invalidateConfig(): void
    {
        // invalidates the complete cached config
        $this->cacheInvalidator->invalidate([
            CachedSystemConfigLoader::CACHE_TAG,
        ]);
    }
}
