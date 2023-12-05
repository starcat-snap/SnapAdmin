<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Facade;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\HookServiceFactory;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\SalesChannelContextAware;
use SnapAdmin\Core\Framework\Script\Execution\Hook;
use SnapAdmin\Core\Framework\Script\Execution\Script;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;

/**
 * @internal
 */
#[Package('system-settings')]
class SystemConfigFacadeHookFactory extends HookServiceFactory
{
    /**
     * @internal
     */
    public function __construct(
        private readonly SystemConfigService $systemConfigService,
        private readonly Connection $connection
    ) {
    }

    public function getName(): string
    {
        return 'config';
    }

    public function factory(Hook $hook, Script $script): SystemConfigFacade
    {
        $salesChannelId = null;

        if ($hook instanceof SalesChannelContextAware) {
            $salesChannelId = $hook->getSalesChannelContext()->getSalesChannelId();
        }

        return new SystemConfigFacade($this->systemConfigService, $this->connection, $script->getScriptAppInformation(), $salesChannelId);
    }
}
