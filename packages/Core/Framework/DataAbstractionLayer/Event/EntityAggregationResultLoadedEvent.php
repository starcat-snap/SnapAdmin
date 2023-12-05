<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResultCollection;
use SnapAdmin\Core\Framework\Event\GenericEvent;
use SnapAdmin\Core\Framework\Event\NestedEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class EntityAggregationResultLoadedEvent extends NestedEvent implements GenericEvent
{
    /**
     * @var AggregationResultCollection
     */
    protected $result;

    /**
     * @var EntityDefinition
     */
    protected $definition;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Context
     */
    protected $context;

    public function __construct(
        EntityDefinition            $definition,
        AggregationResultCollection $result,
        Context                     $context
    )
    {
        $this->result = $result;
        $this->definition = $definition;
        $this->name = $this->definition->getEntityName() . '.aggregation.result.loaded';
        $this->context = $context;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getResult(): AggregationResultCollection
    {
        return $this->result;
    }
}
