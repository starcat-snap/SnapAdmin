<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache;

use SnapAdmin\Core\Framework\Adapter\Translation\AbstractTranslator;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Exception\DecorationPatternException;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;

/**
 * @extends AbstractCacheTracer<mixed|null>
 */
#[Package('core')]
class CacheTracer extends AbstractCacheTracer
{
    /**
     * @internal
     */
    public function __construct(
        private readonly SystemConfigService $config,
        private readonly AbstractTranslator $translator,
        private readonly CacheTagCollection $collection
    ) {
    }

    public function getDecorated(): AbstractCacheTracer
    {
        throw new DecorationPatternException(self::class);
    }

    public function trace(string $key, \Closure $param)
    {
        return $this->collection->trace($key, fn () => $this->translator->trace($key, fn () => $this->config->trace($key, $param)));
    }

    public function get(string $key): array
    {
        return array_merge(
            $this->collection->getTrace($key),
            $this->config->getTrace($key),
            $this->translator->getTrace($key)
        );
    }
}
