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
        $this->removeConfig($event->getScopeId());
    }

    public function setConfig(?string $scopeId, array $config): void
    {
        $this->configs[$this->getKey($scopeId)] = $config;
    }

    public function getConfig(?string $scopeId): ?array
    {
        return $this->configs[$this->getKey($scopeId)] ?? null;
    }

    public function removeConfig(?string $scopeId): void
    {
        if ($scopeId === null) {
            $this->reset();

            return;
        }

        unset($this->configs[$this->getKey($scopeId)]);
    }

    public function reset(): void
    {
        $this->configs = [];
    }

    private function getKey(?string $scopeId): string
    {
        return $scopeId ?? '_global_';
    }
}
