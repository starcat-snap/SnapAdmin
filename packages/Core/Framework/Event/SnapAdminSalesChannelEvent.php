<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Frontend\Channel\ChannelContext;

#[Package('core')]
interface SnapAdminChannelEvent extends SnapAdminEvent
{
    public function getChannelContext(): ChannelContext;
}
