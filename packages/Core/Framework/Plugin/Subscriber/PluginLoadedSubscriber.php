<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Subscriber;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\PluginEntity;
use SnapAdmin\Core\Framework\Plugin\PluginEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @internal
 */
#[Package('core')]
class PluginLoadedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            PluginEvents::PLUGIN_LOADED_EVENT => [
                ['unserialize'],
            ],
        ];
    }

    public function unserialize(EntityLoadedEvent $event): void
    {
        /** @var PluginEntity $plugin */
        foreach ($event->getEntities() as $plugin) {
            if ($plugin->getIconRaw()) {
                $plugin->setIcon(base64_encode($plugin->getIconRaw()));
            }
        }
    }
}
