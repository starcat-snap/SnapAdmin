<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Webhook\ScheduledTask;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use SnapAdmin\Core\Framework\Webhook\Service\WebhookCleanup;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @internal
 */
#[AsMessageHandler(handles: CleanupWebhookEventLogTask::class)]
#[Package('core')]
final class CleanupWebhookEventLogTaskHandler extends ScheduledTaskHandler
{
    /**
     * @internal
     */
    public function __construct(
        EntityRepository $repository,
        private readonly WebhookCleanup $webhookCleanup
    ) {
        parent::__construct($repository);
    }

    public function run(): void
    {
        $this->webhookCleanup->removeOldLogs();
    }
}
