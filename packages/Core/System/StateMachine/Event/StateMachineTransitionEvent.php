<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\NestedEvent;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateEntity;

#[Package('system-settings')]
class StateMachineTransitionEvent extends NestedEvent
{
    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $entityId;

    /**
     * @var StateMachineStateEntity
     */
    protected $fromPlace;

    /**
     * @var StateMachineStateEntity
     */
    protected $toPlace;

    /**
     * @var Context
     */
    protected $context;

    public function __construct(
        string $entityName,
        string $entityId,
        StateMachineStateEntity $fromPlace,
        StateMachineStateEntity $toPlace,
        Context $context
    ) {
        $this->entityName = $entityName;
        $this->entityId = $entityId;
        $this->fromPlace = $fromPlace;
        $this->toPlace = $toPlace;
        $this->context = $context;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function getFromPlace(): StateMachineStateEntity
    {
        return $this->fromPlace;
    }

    public function getToPlace(): StateMachineStateEntity
    {
        return $this->toPlace;
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}
