<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Webhook\EventLog;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<WebhookEventLogEntity>
 */
#[Package('core')]
class WebhookEventLogCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return WebhookEventLogEntity::class;
    }
}
