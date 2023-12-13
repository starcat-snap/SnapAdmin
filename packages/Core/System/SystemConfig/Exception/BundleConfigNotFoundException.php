<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('system-settings')]
class BundleConfigNotFoundException extends SnapAdminHttpException
{
    public function __construct(
        string $configPath,
        string $bundleName
    ) {
        parent::__construct(
            'Could not find "{{ configPath }}" for bundle "{{ bundle }}".',
            [
                'configPath' => $configPath,
                'bundle' => $bundleName,
            ]
        );
    }

    public function getErrorCode(): string
    {
        return 'SYSTEM__BUNDLE_CONFIG_NOT_FOUND';
    }
}
