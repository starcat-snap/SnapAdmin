<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache\Event;

use Psr\Cache\CacheItemInterface;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('core')]
class HttpCacheHitEvent extends Event
{
    public function __construct(
        public readonly CacheItemInterface $item,
        public readonly Request            $request,
        public readonly Response           $response
    )
    {
    }
}
