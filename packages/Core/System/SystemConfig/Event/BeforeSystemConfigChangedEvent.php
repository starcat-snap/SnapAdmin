<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Event;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('system-settings')]
class BeforeSystemConfigChangedEvent extends Event
{
    /**
     * @param array|bool|float|int|string|null $value
     */
    public function __construct(
        private readonly string  $key,
        private                  $value,
        private readonly ?string $scopeId,
        private readonly ?string $scope
    )
    {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array|bool|float|int|string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param array|bool|float|int|string|null $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
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
