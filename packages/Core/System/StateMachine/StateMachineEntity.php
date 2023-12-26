<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineHistory\StateMachineHistoryCollection;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateCollection;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateEntity;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineTransition\StateMachineTransitionCollection;

#[Package('system-settings')]
class StateMachineEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $technicalName;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var StateMachineTransitionCollection|null
     */
    protected $transitions;

    /**
     * @var StateMachineStateCollection|null
     */
    protected $states;

    /**
     * @var string|null
     */
    protected $initialStateId;

    /**
     * @var StateMachineTranslationCollection
     */
    protected $translations;

    /**
     * @var StateMachineHistoryCollection|null
     */
    protected $historyEntries;

    public function getHistoryEntries(): ?StateMachineHistoryCollection
    {
        return $this->historyEntries;
    }

    public function setHistoryEntries(StateMachineHistoryCollection $historyEntries): void
    {
        $this->historyEntries = $historyEntries;
    }

    public function getTechnicalName(): string
    {
        return $this->technicalName;
    }

    public function setTechnicalName(string $technicalName): void
    {
        $this->technicalName = $technicalName;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getTransitions(): ?StateMachineTransitionCollection
    {
        return $this->transitions;
    }

    public function setTransitions(StateMachineTransitionCollection $transitions): void
    {
        $this->transitions = $transitions;
    }

    public function getStates(): ?StateMachineStateCollection
    {
        return $this->states;
    }

    public function setStates(StateMachineStateCollection $states): void
    {
        $this->states = $states;
    }

    public function getInitialState(): ?StateMachineStateEntity
    {
        foreach ($this->states as $state) {
            if ($state->getId() === $this->initialStateId) {
                return $state;
            }
        }

        return null;
    }

    public function getInitialStateId(): ?string
    {
        return $this->initialStateId;
    }

    public function setInitialStateId(string $initialStateId): void
    {
        $this->initialStateId = $initialStateId;
    }

    public function getTranslations(): StateMachineTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(StateMachineTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }
}
