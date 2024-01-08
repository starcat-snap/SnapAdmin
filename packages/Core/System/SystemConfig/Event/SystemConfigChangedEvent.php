<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Event;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('system-settings')]
class SystemConfigChangedEvent extends Event
{
    /**
     * @param array|bool|float|int|string|null $value
     *
     * @internal
     */
    public function __construct(
        private readonly string  $key,
        private                  $value,
        private readonly ?string $scope,
        private readonly ?string $scopeId
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

    public function getScope(): ?string
    {
        return $this->scope;
    }



    public function getScopeId(): ?string
    {
        return $this->scopeId;
    }


}
