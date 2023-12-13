<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Store\Struct\FrwState;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @internal
 */
#[Package('services-settings')]
class FirstRunWizardFinishedEvent extends Event
{
    public function __construct(
        private readonly FrwState $state,
        private readonly FrwState $previousState,
        private readonly Context $context
    ) {
    }

    public function getState(): FrwState
    {
        return $this->state;
    }

    public function getPreviousState(): FrwState
    {
        return $this->previousState;
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}
