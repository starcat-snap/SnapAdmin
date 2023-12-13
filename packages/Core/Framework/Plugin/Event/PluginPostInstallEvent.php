<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Event;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Context\InstallContext;
use SnapAdmin\Core\Framework\Plugin\PluginEntity;

#[Package('core')]
class PluginPostInstallEvent extends PluginLifecycleEvent
{
    public function __construct(
        PluginEntity $plugin,
        private readonly InstallContext $context
    ) {
        parent::__construct($plugin);
    }

    public function getContext(): InstallContext
    {
        return $this->context;
    }
}
