<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script\Execution\Awareness;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;

/**
 * @internal
 */
#[Package('core')]
trait SalesChannelContextAwareTrait
{
    protected SalesChannelContext $salesChannelContext;

    public function getSalesChannelContext(): SalesChannelContext
    {
        return $this->salesChannelContext;
    }
}
