<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;
use SaagFrontend\Channel\Entity\PartialChannelEntityLoadedEvent;
use SaagFrontend\Channel\Entity\ChannelEntityLoadedEvent;
use SaagFrontend\Channel\ChannelContext;

/**
 * @internal
 */
#[Package('core')]
class EntityLoadedEventFactory
{
    public function __construct(private readonly DefinitionInstanceRegistry $registry)
    {
    }

    /**
     * @param array<mixed> $entities
     */
    public function create(array $entities, Context $context): EntityLoadedContainerEvent
    {
        $mapping = $this->recursion($entities, []);

        $generator = fn(EntityDefinition $definition, array $entities) => new EntityLoadedEvent($definition, $entities, $context);

        return $this->buildEvents($mapping, $generator, $context);
    }

    /**
     * @param array<mixed> $entities
     */
    public function createPartial(array $entities, Context $context): EntityLoadedContainerEvent
    {
        $mapping = $this->recursion($entities, []);

        $generator = fn(EntityDefinition $definition, array $entities) => new PartialEntityLoadedEvent($definition, $entities, $context);

        return $this->buildEvents($mapping, $generator, $context);
    }

    /**
     * @param array<mixed> $entities
     *
     * @return EntityLoadedContainerEvent[]
     */
    public function createForChannel(array $entities, ChannelContext $context): array
    {
        $mapping = $this->recursion($entities, []);

        $generator = fn(EntityDefinition $definition, array $entities) => new EntityLoadedEvent($definition, $entities, $context->getContext());

        $salesGenerator = fn(EntityDefinition $definition, array $entities) => new ChannelEntityLoadedEvent($definition, $entities, $context);

        return [
            $this->buildEvents($mapping, $generator, $context->getContext()),
            $this->buildEvents($mapping, $salesGenerator, $context->getContext()),
        ];
    }

    /**
     * @param array<mixed> $entities
     *
     * @return EntityLoadedContainerEvent[]
     */
    public function createPartialForChannel(array $entities, ChannelContext $context): array
    {
        $mapping = $this->recursion($entities, []);

        $generator = fn(EntityDefinition $definition, array $entities) => new PartialEntityLoadedEvent($definition, $entities, $context->getContext());

        $salesGenerator = fn(EntityDefinition $definition, array $entities) => new PartialChannelEntityLoadedEvent($definition, $entities, $context);

        return [
            $this->buildEvents($mapping, $generator, $context->getContext()),
            $this->buildEvents($mapping, $salesGenerator, $context->getContext()),
        ];
    }

    /**
     * @param array<string, list<Entity>> $mapping
     */
    private function buildEvents(array $mapping, \Closure $generator, Context $context): EntityLoadedContainerEvent
    {
        $events = [];
        foreach ($mapping as $name => $entities) {
            $definition = $this->registry->getByEntityName($name);

            $events[] = $generator($definition, $entities);
        }

        return new EntityLoadedContainerEvent($context, $events);
    }

    /**
     * @param array<mixed> $entities
     * @param array<string, list<Entity>> $mapping
     *
     * @return array<string, list<Entity>>
     */
    private function recursion(array $entities, array $mapping): array
    {
        foreach ($entities as $entity) {
            if (!$entity instanceof Entity && !$entity instanceof EntityCollection) {
                continue;
            }

            if ($entity instanceof EntityCollection) {
                $mapping = $this->recursion($entity->getElements(), $mapping);
            } else {
                $mapping = $this->map($entity, $mapping);
            }
        }

        return $mapping;
    }

    /**
     * @param array<string, list<Entity>> $mapping
     *
     * @return array<string, list<Entity>>
     */
    private function map(Entity $entity, array $mapping): array
    {
        $mapping[$entity->getInternalEntityName()][] = $entity;

        $vars = $entity->getVars();
        foreach ($vars as $value) {
            if ($value instanceof Entity) {
                $mapping = $this->map($value, $mapping);

                continue;
            }

            if ($value instanceof Collection) {
                $value = $value->getElements();
            }
            if (!\is_array($value)) {
                continue;
            }

            $mapping = $this->recursion($value, $mapping);
        }

        return $mapping;
    }
}
