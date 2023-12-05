<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @final
 */
#[Package('core')]
class AggregatorResult extends Struct
{
    public function __construct(
        private readonly AggregationResultCollection $aggregations,
        private readonly Context $context,
        private readonly Criteria $criteria
    ) {
    }

    public function getAggregations(): AggregationResultCollection
    {
        return $this->aggregations;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }

    public function getApiAlias(): string
    {
        return 'dal_aggregator_result';
    }
}
