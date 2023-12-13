<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('business-ops')]
class BusinessEventCollectorEvent extends NestedEvent
{
    final public const NAME = 'collect.business-events';

    public function __construct(
        private readonly BusinessEventCollectorResponse $events,
        private readonly Context $context
    ) {
    }

    public function getCollection(): BusinessEventCollectorResponse
    {
        return $this->events;
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}
