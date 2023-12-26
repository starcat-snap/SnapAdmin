<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<StateMachineStateEntity>
 */
#[Package('system-settings')]
class StateMachineStateCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'state_machine_state_collection';
    }

    protected function getExpectedClass(): string
    {
        return StateMachineStateEntity::class;
    }
}
