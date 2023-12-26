<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<NumberRangeEntity>
 */
#[Package('core')]
class NumberRangeCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'number_range_collection';
    }

    protected function getExpectedClass(): string
    {
        return NumberRangeEntity::class;
    }
}
