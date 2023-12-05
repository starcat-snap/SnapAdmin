<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache\Script\Facade;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class WrittenEventScriptFacade
{
    public function __construct(private readonly EntityWrittenContainerEvent $containerEvent)
    {
    }

    /**
     * `getIds()` filters the written events down to the events of a single entity.
     *
     * @param string $entity The entity for which the events should be filtered.
     *
     * @return WrittenEventIdCollection The id collection for the written events which allows further filtering.
     */
    public function getIds(string $entity): WrittenEventIdCollection
    {
        $writtenEvent = $this->containerEvent->getEventByEntityName($entity);

        return new WrittenEventIdCollection(
            $writtenEvent ? $writtenEvent->getWriteResults() : []
        );
    }
}
