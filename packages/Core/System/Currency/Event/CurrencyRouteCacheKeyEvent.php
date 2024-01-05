<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Currency\Event;

use SnapAdmin\Core\Framework\Adapter\Cache\StoreApiRouteCacheKeyEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('system')]
class CurrencyRouteCacheKeyEvent extends StoreApiRouteCacheKeyEvent
{
}
