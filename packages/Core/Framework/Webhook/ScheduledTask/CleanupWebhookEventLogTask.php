<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Webhook\ScheduledTask;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

/**
 * @deprecated tag:v6.6.0 - Will be internal - reason:visibility-change
 */
#[Package('core')]
class CleanupWebhookEventLogTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'webhook_event_log.cleanup';
    }

    public static function getDefaultInterval(): int
    {
        return 86400; // 24 hours
    }
}
