<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
#[\Attribute(\Attribute::TARGET_CLASS)]
class IsFlowEventAware
{
}
