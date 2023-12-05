<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;

#[Package('core')]
interface SnapAdminSalesChannelEvent extends SnapAdminEvent
{
    public function getSalesChannelContext(): SalesChannelContext;
}
