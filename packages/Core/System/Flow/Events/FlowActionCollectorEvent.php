<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Events;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\NestedEvent;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Flow\Api\FlowActionCollectorResponse;

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
