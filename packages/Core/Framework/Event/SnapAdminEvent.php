<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
interface SnapAdminEvent
{
    public function getContext(): Context;
}
