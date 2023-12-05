<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Package('core')]
class InvalidateCacheTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'snap.invalidate_cache';
    }

    public static function getDefaultInterval(): int
    {
        return 20;
    }

    public static function shouldRun(ParameterBagInterface $bag): bool
    {
        return $bag->get('snap.cache.invalidation.delay') > 0;
    }
}
