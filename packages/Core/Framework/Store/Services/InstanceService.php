<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Services;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Kernel;

/**
 * @internal
 */
#[Package('services-settings')]
class InstanceService
{
    public function __construct(
        private readonly string $snapVersion,
        private readonly ?string $instanceId
    ) {
    }

    public function getSnapAdminVersion(): string
    {
        if ($this->snapVersion === Kernel::SHOPWARE_FALLBACK_VERSION) {
            return '___VERSION___';
        }

        return $this->snapVersion;
    }

    public function getInstanceId(): ?string
    {
        return $this->instanceId;
    }
}
