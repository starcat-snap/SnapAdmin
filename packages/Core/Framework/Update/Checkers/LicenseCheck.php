<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Update\Checkers;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Store\Services\StoreClient;
use SnapAdmin\Core\Framework\Update\Struct\ValidationResult;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;

#[Package('system-settings')]
class LicenseCheck
{
    /**
     * @internal
     */
    public function __construct(
        private readonly SystemConfigService $systemConfigService,
        private readonly StoreClient $storeClient
    ) {
    }

    public function check(): ValidationResult
    {
        $licenseHost = $this->systemConfigService->get('core.store.licenseHost');

        if (empty($licenseHost) || $this->storeClient->isShopUpgradeable()) {
            return new ValidationResult('validSnapAdminLicense', true, 'validSnapAdminLicense');
        }

        return new ValidationResult('invalidSnapAdminLicense', false, 'invalidSnapAdminLicense');
    }
}
