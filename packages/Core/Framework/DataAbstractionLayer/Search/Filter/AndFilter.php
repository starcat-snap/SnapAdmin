<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class AndFilter extends MultiFilter
{
    public function __construct(array $queries = [])
    {
        parent::__construct(self::CONNECTION_AND, $queries);
    }
}
