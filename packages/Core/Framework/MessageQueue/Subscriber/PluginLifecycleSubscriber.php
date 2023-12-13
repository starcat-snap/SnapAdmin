<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\MessageQueue\Subscriber;

use Psr\Cache\CacheItemPoolInterface;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\Registry\TaskRegistry;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostActivateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostDeactivateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostUpdateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\EventListener\StopWorkerOnRestartSignalListener;

/**
 * @internal
 */
#[Package('system-settings')]
final class PluginLifecycleSubscriber implements EventSubscriberInterface
{
    /**
     * @internal
     */
    public function __construct(
        private readonly TaskRegistry $registry,
        private readonly CacheItemPoolInterface $restartSignalCachePool
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PluginPostActivateEvent::class => 'afterPluginStateChange',
            PluginPostDeactivateEvent::class => 'afterPluginStateChange',
            PluginPostUpdateEvent::class => 'afterPluginStateChange',
        ];
    }

    public function afterPluginStateChange(): void
    {
        $this->registry->registerTasks();

        // signal worker restart
        $cacheItem = $this->restartSignalCachePool->getItem(StopWorkerOnRestartSignalListener::RESTART_REQUESTED_TIMESTAMP_KEY);
        $cacheItem->set(microtime(true));
        $this->restartSignalCachePool->save($cacheItem);
    }
}
