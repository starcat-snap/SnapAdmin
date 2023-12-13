<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Event;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Context\ActivateContext;
use SnapAdmin\Core\Framework\Plugin\PluginEntity;

#[Package('core')]
class PluginPostDeactivationFailedEvent extends PluginLifecycleEvent
{
    public function __construct(
        PluginEntity $plugin,
        private readonly ActivateContext $context,
        private readonly ?\Throwable $exception = null
    ) {
        parent::__construct($plugin);
    }

    public function getContext(): ActivateContext
    {
        return $this->context;
    }

    public function getException(): ?\Throwable
    {
        return $this->exception;
    }
}
