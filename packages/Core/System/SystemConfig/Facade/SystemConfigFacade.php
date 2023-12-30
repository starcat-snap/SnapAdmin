<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Facade;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;

/**
 * The `config` service allows you to access the shop's and your app's configuration values.
 *
 * @script-service miscellaneous
 */
#[Package('system-settings')]
class SystemConfigFacade
{
    private const PRIVILEGE = 'system_config:read';

    private array $appData = [];

    /**
     * @internal
     */
    public function __construct(
        private readonly SystemConfigService $systemConfigService
    ) {
    }

    /**
     * The `get()` method allows you to access all config values of the store.
     * Notice that your app needs the `system_config:read` privilege to use this method.
     *
     * @param string $key The key of the configuration value e.g. `core.listing.defaultSorting`.
     *
     * @return array|bool|float|int|string|null
     *
     * @example test-config/script.twig 4 1 Read an arbitrary system_config value.
     */
    public function get(string $key)
    {
        return $this->systemConfigService->get($key);
    }
}
