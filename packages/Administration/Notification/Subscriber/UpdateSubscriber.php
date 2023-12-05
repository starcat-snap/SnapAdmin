<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Notification\Subscriber;

use SnapAdmin\Administration\Notification\NotificationService;
use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Update\Event\UpdatePostFinishEvent;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @internal
 */
#[Package('system-settings')]
class UpdateSubscriber implements EventSubscriberInterface
{
    /**
     * @internal
     */
    public function __construct(
        private readonly NotificationService $notificationService
    )
    {
    }

    /**
     * @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UpdatePostFinishEvent::class => [
                ['updateFinishedDone', -9999],
            ],
        ];
    }

    /**
     * @internal
     */
    public function updateFinishedDone(UpdatePostFinishEvent $event): void
    {
        $status = 'success';
        $message = 'Updated successfully to version ' . $event->getNewVersion();
        if ($event->getPostUpdateMessage() !== '') {
            $status = 'warning';
            $message .= \PHP_EOL . $event->getPostUpdateMessage();
        }

        $source = $event->getContext()->getSource();
        $integrationId = null;
        $createdByUserId = null;
        if ($source instanceof AdminApiSource) {
            $integrationId = $source->getIntegrationId();
            $createdByUserId = $source->getUserId();
        }

        $this->notificationService->createNotification(
            [
                'id' => Uuid::randomHex(),
                'status' => $status,
                'message' => $message,
                'adminOnly' => true,
                'requiredPrivileges' => [],
                'createdByIntegrationId' => $integrationId,
                'createdByUserId' => $createdByUserId,
            ],
            $event->getContext()
        );
    }
}
