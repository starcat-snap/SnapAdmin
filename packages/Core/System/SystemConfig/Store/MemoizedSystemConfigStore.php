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
        $this->removeConfig($event->getScopeId(), $event->getScope());
    }

    public function setConfig(?string $scopeId, ?string $scope, array $config): void
    {
        $this->configs[$this->getKey($scopeId,$scope)] = $config;
    }

    public function getConfig(?string $scopeId, ?string $scope): ?array
    {
        return $this->configs[$this->getKey($scopeId,$scope)] ?? null;
    }

    public function removeConfig(?string $scopeId, ?string $scope): void
    {
        if ($scopeId === null) {
            $this->reset();

            return;
        }

        unset($this->configs[$this->getKey($scopeId, $scope)]);
    }

    public function reset(): void
    {
        $this->configs = [];
    }

    private function getKey(?string $scopeId, ?string $scope): string
    {
        return $scopeId && $scope ? $scope . '-' . $scopeId : '_global_';
    }
}
