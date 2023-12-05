<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\MessageQueue\ScheduledTask;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\AsyncMessageInterface;

#[Package('core')]
class RegisterScheduledTaskMessage implements AsyncMessageInterface
{
}
