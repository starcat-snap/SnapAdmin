<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Events;

use SnapAdmin\Core\Content\Flow\Api\FlowActionCollectorResponse;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\NestedEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class FlowActionCollectorEvent extends NestedEvent
{
    public function __construct(
        private readonly FlowActionCollectorResponse $flowActionCollectorResponse,
        private readonly Context $context
    ) {
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getCollection(): FlowActionCollectorResponse
    {
        return $this->flowActionCollectorResponse;
    }
}
