<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\SnapAdminChannelEvent;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Frontend\Channel\ChannelContext;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('core')]
class ChannelContextResolvedEvent extends Event implements SnapAdminChannelEvent
{
    public function __construct(
        private readonly ChannelContext $channelContext,
        private readonly string              $usedToken
    )
    {
    }

    public function getChannelContext(): ChannelContext
    {
        return $this->channelContext;
    }

    public function getContext(): Context
    {
        return $this->channelContext->getContext();
    }

    public function getUsedToken(): string
    {
        return $this->usedToken;
    }
}
