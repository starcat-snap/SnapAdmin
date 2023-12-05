<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Store;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SystemConfig\Event\SystemConfigChangedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * @internal
 */
#[Package('system-settings')]
final class MemoizedSystemConfigStore implements EventSubscriberInterface, ResetInterface
{
    /**
     * @var array[]
     */
    private array $configs = [];

    public static function getSubscribedEvents(): array
    {
        return [
            SystemConfigChangedEvent::class => [
                ['onValueChanged', 1500],
            ],
        ];
    }

    public function onValueChanged(SystemConfigChangedEvent $event): void
    {
        $this->removeConfig($event->getChannelId());
    }

    public function setConfig(?string $channelId, array $config): void
    {
        $this->configs[$this->getKey($channelId)] = $config;
    }

    public function getConfig(?string $channelId): ?array
    {
        return $this->configs[$this->getKey($channelId)] ?? null;
    }

    public function removeConfig(?string $channelId): void
    {
        if ($channelId === null) {
            $this->reset();

            return;
        }

        unset($this->configs[$this->getKey($channelId)]);
    }

    public function reset(): void
    {
        $this->configs = [];
    }

    private function getKey(?string $channelId): string
    {
        return $channelId ?? '_global_';
    }
}
