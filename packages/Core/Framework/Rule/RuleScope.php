<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Rule;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;

#[Package('services-settings')]
abstract class RuleScope
{
    abstract public function getContext(): Context;

    abstract public function getSalesChannelContext(): SalesChannelContext;

    public function getCurrentTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
