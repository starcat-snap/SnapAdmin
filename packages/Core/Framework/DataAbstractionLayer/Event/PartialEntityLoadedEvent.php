<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\PartialEntity;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class PartialEntityLoadedEvent extends EntityLoadedEvent
{
    /**
     * @var PartialEntity[]
     */
    protected $entities;

    /**
     * @param PartialEntity[] $entities
     */
    public function __construct(
        EntityDefinition $definition,
        array            $entities,
        Context          $context
    )
    {
        parent::__construct($definition, $entities, $context);
        $this->name = $this->definition->getEntityName() . '.partial_loaded';
    }

    /**
     * @return PartialEntity[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }
}
