<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class AvgResult extends AggregationResult
{
    public function __construct(
        string          $name,
        protected float $avg
    )
    {
        parent::__construct($name);
    }

    public function getAvg(): float
    {
        return $this->avg;
    }
}
