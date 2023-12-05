<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\MessageQueue\fixtures;

use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

/**
 * @internal
 */
class TestTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return self::class;
    }

    public static function getDefaultInterval(): int
    {
        return 1;
    }
}
