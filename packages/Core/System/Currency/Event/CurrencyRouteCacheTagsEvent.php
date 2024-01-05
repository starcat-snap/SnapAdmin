<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Currency\Event;

use SnapAdmin\Core\Framework\Adapter\Cache\StoreApiRouteCacheTagsEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('system')]
class CurrencyRouteCacheTagsEvent extends StoreApiRouteCacheTagsEvent
{
}
