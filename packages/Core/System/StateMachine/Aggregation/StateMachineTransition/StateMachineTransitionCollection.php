<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineTransition;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<StateMachineTransitionEntity>
 */
#[Package('system-settings')]
class StateMachineTransitionCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'state_machine_transition_collection';
    }

    protected function getExpectedClass(): string
    {
        return StateMachineTransitionEntity::class;
    }
}
