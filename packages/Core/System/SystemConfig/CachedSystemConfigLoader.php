<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig;

use SnapAdmin\Core\Framework\Adapter\Cache\CacheValueCompressor;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Package('system-settings')]
class CachedSystemConfigLoader extends AbstractSystemConfigLoader
{
    final public const CACHE_TAG = 'system-config';

    /**
     * @internal
     */
    public function __construct(
        private readonly AbstractSystemConfigLoader $decorated,
        private readonly CacheInterface             $cache
    )
    {
    }

    public function getDecorated(): AbstractSystemConfigLoader
    {
        return $this->decorated;
    }

    public function load(): array
    {
        $key = 'system-config-';

        $value = $this->cache->get($key, function (ItemInterface $item)  {
            $config = $this->getDecorated()->load();

            $item->tag([self::CACHE_TAG]);

            return CacheValueCompressor::compress($config);
        });

        return CacheValueCompressor::uncompress($value);
    }
}
