<?php declare(strict_types=1);

namespace SwagTest;

use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @final
 *
 * @internal
 */
#[AsMessageHandler(handles: SwagTestTask::class)]
class SwagTestTaskHandler extends ScheduledTaskHandler
{
    public function run(): void
    {
    }
}
