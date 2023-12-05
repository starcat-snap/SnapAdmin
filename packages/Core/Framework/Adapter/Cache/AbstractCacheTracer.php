<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @template TCachedContent
 */
#[Package('core')]
abstract class AbstractCacheTracer
{
    /**
     * @return AbstractCacheTracer<TCachedContent>
     */
    abstract public function getDecorated(): AbstractCacheTracer;

    /**
     * @template TReturn of TCachedContent
     *
     * @param \Closure(): TReturn $param
     *
     * @return TReturn
     */
    abstract public function trace(string $key, \Closure $param);

    /**
     * @return array<string>
     */
    abstract public function get(string $key): array;
}
