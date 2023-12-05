<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\SalesChannelEntity;

#[Package('system-settings')]
class SystemConfigEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $configurationKey;

    /**
     * @var array|bool|float|int|string|null
     */
    protected $configurationValue;

    /**
     * @var string|null
     */
    protected $salesChannelId;

    /**
     * @var SalesChannelEntity|null
     */
    protected $salesChannel;

    public function getConfigurationKey(): string
    {
        return $this->configurationKey;
    }

    public function setConfigurationKey(string $configurationKey): void
    {
        $this->configurationKey = $configurationKey;
    }

    /**
     * @return array|bool|float|int|string|null
     */
    public function getConfigurationValue()
    {
        return $this->configurationValue;
    }

    /**
     * @param array|bool|float|int|string|null $configurationValue
     */
    public function setConfigurationValue($configurationValue): void
    {
        $this->configurationValue = $configurationValue;
    }

    public function getSalesChannelId(): ?string
    {
        return $this->salesChannelId;
    }

    public function setSalesChannelId(?string $salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }

    public function getSalesChannel(): ?SalesChannelEntity
    {
        return $this->salesChannel;
    }

    public function setSalesChannel(SalesChannelEntity $salesChannel): void
    {
        $this->salesChannel = $salesChannel;
    }
}
