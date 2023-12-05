<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Event;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Context\UninstallContext;
use SnapAdmin\Core\Framework\Plugin\PluginEntity;

#[Package('core')]
class PluginPostUninstallEvent extends PluginLifecycleEvent
{
    public function __construct(
        PluginEntity                      $plugin,
        private readonly UninstallContext $context
    )
    {
        parent::__construct($plugin);
    }

    public function getContext(): UninstallContext
    {
        return $this->context;
    }
}
