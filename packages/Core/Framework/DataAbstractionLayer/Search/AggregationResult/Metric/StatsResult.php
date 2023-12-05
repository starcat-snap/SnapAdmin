<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class StatsResult extends AggregationResult
{
    public function __construct(
        string           $name,
        protected mixed  $min,
        protected mixed  $max,
        protected ?float $avg,
        protected ?float $sum
    )
    {
        parent::__construct($name);
    }

    public function getMin(): mixed
    {
        return $this->min;
    }

    public function getMax(): mixed
    {
        return $this->max;
    }

    public function getAvg(): ?float
    {
        return $this->avg;
    }

    public function getSum(): ?float
    {
        return $this->sum;
    }
}
