<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Rule\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\NestedEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class RuleIndexerEvent extends NestedEvent
{
    public function __construct(
        private readonly array $ids,
        private readonly Context $context,
        private readonly array $skip = []
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

    public function getSkip(): array
    {
        return $this->skip;
    }
}
