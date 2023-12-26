<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('system-settings')]
class Transition
{
    public function __construct(
        private readonly string $entityName,
        private readonly string $entityId,
        private readonly string $transitionName,
        private readonly string $stateFieldName
    ) {
    }

    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getTransitionName(): string
    {
        return $this->transitionName;
    }

    public function getStateFieldName(): string
    {
        return $this->stateFieldName;
    }
}
