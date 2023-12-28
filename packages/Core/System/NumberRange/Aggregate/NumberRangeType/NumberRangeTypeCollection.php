<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeType;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<NumberRangeTypeEntity>
 */
#[Package('system-settings')]
class NumberRangeTypeCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'number_range_type_collection';
    }

    protected function getExpectedClass(): string
    {
        return NumberRangeTypeEntity::class;
    }
}
