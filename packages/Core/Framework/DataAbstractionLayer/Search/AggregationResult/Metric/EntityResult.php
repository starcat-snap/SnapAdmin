<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Metric;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 *
 * @template TEntityCollection of EntityCollection
 */
#[Package('core')]
class EntityResult extends AggregationResult
{
    /**
     * @param TEntityCollection $entities
     */
    public function __construct(string $name, protected EntityCollection $entities)
    {
        parent::__construct($name);
    }

    /**
     * @return TEntityCollection
     */
    public function getEntities(): EntityCollection
    {
        return $this->entities;
    }

    public function add(Entity $entity): void
    {
        $this->entities->add($entity);
    }
}
