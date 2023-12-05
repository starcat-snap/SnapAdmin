<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class RangeResult extends AggregationResult
{
    /**
     * @param array<string, int> $ranges
     */
    public function __construct(
        string          $name,
        protected array $ranges
    )
    {
        parent::__construct($name);
    }

    /**
     * @return array<string, int>
     */
    public function getRanges(): array
    {
        return $this->ranges;
    }
}
