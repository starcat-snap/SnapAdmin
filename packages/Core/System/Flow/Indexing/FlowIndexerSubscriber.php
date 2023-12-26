<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Indexing;

use SnapAdmin\Core\Framework\App\Event\AppActivatedEvent;
use SnapAdmin\Core\Framework\App\Event\AppDeactivatedEvent;
use SnapAdmin\Core\Framework\App\Event\AppDeletedEvent;
use SnapAdmin\Core\Framework\App\Event\AppInstalledEvent;
use SnapAdmin\Core\Framework\App\Event\AppUpdatedEvent;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\MessageQueue\IterateEntityIndexerMessage;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostActivateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostDeactivateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostInstallEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostUninstallEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostUpdateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @internal
 */
#[Package('services-settings')]
class FlowIndexerSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PluginPostInstallEvent::class => 'refreshPlugin',
            PluginPostActivateEvent::class => 'refreshPlugin',
            PluginPostUpdateEvent::class => 'refreshPlugin',
            PluginPostDeactivateEvent::class => 'refreshPlugin',
            PluginPostUninstallEvent::class => 'refreshPlugin',
            AppInstalledEvent::class => 'refreshPlugin',
            AppUpdatedEvent::class => 'refreshPlugin',
            AppActivatedEvent::class => 'refreshPlugin',
            AppDeletedEvent::class => 'refreshPlugin',
            AppDeactivatedEvent::class => 'refreshPlugin',
        ];
    }

    public function refreshPlugin(): void
    {
        // Schedule indexer to update flows
        $this->messageBus->dispatch(new IterateEntityIndexerMessage(FlowIndexer::NAME, null));
    }
}
