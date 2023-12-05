<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class BeforeSendRedirectResponseEvent extends BeforeSendResponseEvent
{
}
