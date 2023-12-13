<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class CountResult extends AggregationResult
{
    public function __construct(
        string $name,
        protected int $count
    ) {
        parent::__construct($name);
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
