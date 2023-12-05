<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\SnapAdminSalesChannelEvent;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('core')]
class SalesChannelContextResolvedEvent extends Event implements SnapAdminSalesChannelEvent
{
    public function __construct(
        private readonly SalesChannelContext $salesChannelContext,
        private readonly string $usedToken
    ) {
    }

    public function getSalesChannelContext(): SalesChannelContext
    {
        return $this->salesChannelContext;
    }

    public function getContext(): Context
    {
        return $this->salesChannelContext->getContext();
    }

    public function getUsedToken(): string
    {
        return $this->usedToken;
    }
}
