<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Language\Event;

use SnapAdmin\Core\Framework\Adapter\Cache\StoreApiRouteCacheTagsEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('buyers-experience')]
class LanguageRouteCacheTagsEvent extends StoreApiRouteCacheTagsEvent
{
}
