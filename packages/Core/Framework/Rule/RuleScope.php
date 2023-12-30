<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Rule;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
abstract class RuleScope
{
    abstract public function getContext(): Context;

    public function getCurrentTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
