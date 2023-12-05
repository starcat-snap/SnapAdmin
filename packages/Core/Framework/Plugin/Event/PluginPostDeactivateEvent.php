<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Event;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Context\DeactivateContext;
use SnapAdmin\Core\Framework\Plugin\PluginEntity;

#[Package('core')]
class PluginPostDeactivateEvent extends PluginLifecycleEvent
{
    public function __construct(
        PluginEntity $plugin,
        private readonly DeactivateContext $context
    ) {
        parent::__construct($plugin);
    }

    public function getContext(): DeactivateContext
    {
        return $this->context;
    }
}
