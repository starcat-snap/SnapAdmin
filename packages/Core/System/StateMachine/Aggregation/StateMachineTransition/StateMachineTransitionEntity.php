<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineTransition;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateEntity;
use SnapAdmin\Core\System\StateMachine\StateMachineEntity;

#[Package('system-settings')]
class StateMachineTransitionEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $actionName;

    /**
     * @var string
     */
    protected $stateMachineId;

    /**
     * @var StateMachineEntity|null
     */
    protected $stateMachine;

    /**
     * @var string
     */
    protected $fromStateId;

    /**
     * @var StateMachineStateEntity|null
     */
    protected $fromStateMachineState;

    /**
     * @var string
     */
    protected $toStateId;

    /**
     * @var StateMachineStateEntity|null
     */
    protected $toStateMachineState;

    public function getStateMachineId(): string
    {
        return $this->stateMachineId;
    }

    public function setStateMachineId(string $stateMachineId): void
    {
        $this->stateMachineId = $stateMachineId;
    }

    public function getStateMachine(): ?StateMachineEntity
    {
        return $this->stateMachine;
    }

    public function setStateMachine(StateMachineEntity $stateMachine): void
    {
        $this->stateMachine = $stateMachine;
    }

    public function getFromStateId(): string
    {
        return $this->fromStateId;
    }

    public function setFromStateId(string $fromStateId): void
    {
        $this->fromStateId = $fromStateId;
    }

    public function getFromStateMachineState(): ?StateMachineStateEntity
    {
        return $this->fromStateMachineState;
    }

    public function setFromStateMachineState(StateMachineStateEntity $fromStateMachineState): void
    {
        $this->fromStateMachineState = $fromStateMachineState;
    }

    public function getToStateId(): string
    {
        return $this->toStateId;
    }

    public function setToStateId(string $toStateId): void
    {
        $this->toStateId = $toStateId;
    }

    public function getToStateMachineState(): ?StateMachineStateEntity
    {
        return $this->toStateMachineState;
    }

    public function setToStateMachineState(StateMachineStateEntity $toStateMachineState): void
    {
        $this->toStateMachineState = $toStateMachineState;
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }

    public function setActionName(string $actionName): void
    {
        $this->actionName = $actionName;
    }
}
