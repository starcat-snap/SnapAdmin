<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\Subscriber;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexerRegistry;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @internal
 */
#[Package('core')]
class EntityIndexingSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityIndexerRegistry $indexerRegistry)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [EntityWrittenContainerEvent::class => [['refreshIndex', 1000]]];
    }

    public function refreshIndex(EntityWrittenContainerEvent $event): void
    {
        $this->indexerRegistry->refresh($event);
    }
}
