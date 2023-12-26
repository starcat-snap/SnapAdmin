<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineHistory;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<StateMachineHistoryEntity>
 */
#[Package('system-settings')]
class StateMachineHistoryCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'state_machine_history_collection';
    }

    protected function getExpectedClass(): string
    {
        return StateMachineHistoryEntity::class;
    }
}
