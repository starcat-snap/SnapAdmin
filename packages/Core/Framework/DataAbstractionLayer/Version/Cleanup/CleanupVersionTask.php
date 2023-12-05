<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Version\Cleanup;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

#[Package('core')]
class CleanupVersionTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'version.cleanup';
    }

    public static function getDefaultInterval(): int
    {
        return 86400; // 24 hours
    }
}
