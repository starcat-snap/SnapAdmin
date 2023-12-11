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
        $this->removeConfig();
    }

    public function setConfig(array $config): void
    {
        $this->configs[$this->getKey()] = $config;
    }

    public function getConfig(): ?array
    {
        return $this->configs[$this->getKey()] ?? null;
    }

    public function removeConfig(): void
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->configs = [];
    }

    private function getKey(): string
    {
        return '_global_';
    }
}
