<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Webhook;

use SnapAdmin\Core\Framework\App\AppEntity;
use SnapAdmin\Core\Framework\App\Event\AppActivatedEvent;
use SnapAdmin\Core\Framework\App\Event\AppDeactivatedEvent;
use SnapAdmin\Core\Framework\App\Event\AppDeletedEvent;
use SnapAdmin\Core\Framework\App\Event\AppInstalledEvent;
use SnapAdmin\Core\Framework\App\Event\AppUpdatedEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
interface Hookable
{
    public const HOOKABLE_EVENTS = [
        AppActivatedEvent::class => AppActivatedEvent::NAME,
        AppDeactivatedEvent::class => AppDeactivatedEvent::NAME,
        AppDeletedEvent::class => AppDeletedEvent::NAME,
        AppInstalledEvent::class => AppInstalledEvent::NAME,
        AppUpdatedEvent::class => AppUpdatedEvent::NAME,
    ];

    public function getName(): string;

    /**
     * @param AppEntity|null $app - @deprecated tag:v6.6.0 parameter $app will be required in v6.6.0.0
     *
     * @return array<mixed>
     */
    public function getWebhookPayload(): array;

    /**
     * returns if it is allowed to dispatch the event to given app with given permissions
     */
    public function isAllowed(string $appId, AclPrivilegeCollection $permissions): bool;
}
