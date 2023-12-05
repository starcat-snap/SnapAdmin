<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Sorting;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class CountSorting extends FieldSorting
{
    protected string $type = 'count';
}
