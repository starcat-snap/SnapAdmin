<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<StateMachineEntity>
 */
#[Package('core')]
class StateMachineCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'state_machine_collection';
    }

    protected function getExpectedClass(): string
    {
        return StateMachineEntity::class;
    }
}
