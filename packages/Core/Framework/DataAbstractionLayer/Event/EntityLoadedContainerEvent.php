<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\NestedEvent;
use SnapAdmin\Core\Framework\Event\NestedEventCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class EntityLoadedContainerEvent extends NestedEvent
{
    public function __construct(
        private readonly Context $context,
        private readonly array   $events
    )
    {
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getEvents(): ?NestedEventCollection
    {
        return new NestedEventCollection($this->events);
    }
}
