<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeState;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<NumberRangeStateEntity>
 */
#[Package('system-settings')]
class NumberRangeStateCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'number_range_state_collection';
    }

    protected function getExpectedClass(): string
    {
        return NumberRangeStateEntity::class;
    }
}
