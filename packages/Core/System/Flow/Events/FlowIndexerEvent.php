<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Events;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\NestedEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class FlowIndexerEvent extends NestedEvent
{
    public function __construct(
        private readonly array $ids,
        private readonly Context $context
    ) {
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}
