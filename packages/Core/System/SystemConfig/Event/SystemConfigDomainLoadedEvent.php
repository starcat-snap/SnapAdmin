<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Event;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('system-settings')]
class SystemConfigDomainLoadedEvent extends Event
{
    public function __construct(
        private readonly string $domain,
        private array $config,
        private readonly bool $inherit,
        private readonly ?string $scopeId,
        private readonly ?string $scope
    ) {
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function isInherit(): bool
    {
        return $this->inherit;
    }

    public function getScopeId(): ?string
    {
        return $this->scopeId;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }


}
