<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Log\ScheduledTask;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

#[Package('core')]
class LogCleanupTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'log_entry.cleanup';
    }

    public static function getDefaultInterval(): int
    {
        return 86400; // 24 hours
    }
}
