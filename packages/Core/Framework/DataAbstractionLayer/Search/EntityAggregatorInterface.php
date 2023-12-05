<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
interface EntityAggregatorInterface
{
    public function aggregate(EntityDefinition $definition, Criteria $criteria, Context $context): AggregationResultCollection;
}
