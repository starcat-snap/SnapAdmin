<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Indexing;

use SnapAdmin\Core\Content\Flow\Events\FlowIndexerEvent;
use SnapAdmin\Core\Content\Flow\FlowDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\Common\IteratorFactory;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexingMessage;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Exception\DecorationPatternException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @final
 */
#[Package('services-settings')]
class FlowIndexer extends EntityIndexer
{
    public const NAME = 'flow.indexer';

    /**
     * @internal
     */
    public function __construct(
        private readonly IteratorFactory $iteratorFactory,
        private readonly EntityRepository $repository,
        private readonly FlowPayloadUpdater $payloadUpdater,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function iterate(?array $offset): ?EntityIndexingMessage
    {
        $iterator = $this->iteratorFactory->createIterator($this->repository->getDefinition(), $offset);

        $ids = $iterator->fetch();

        if (empty($ids)) {
            return null;
        }

        return new FlowIndexingMessage(array_values($ids), $iterator->getOffset());
    }

    public function update(EntityWrittenContainerEvent $event): ?EntityIndexingMessage
    {
        $updates = $event->getPrimaryKeys(FlowDefinition::ENTITY_NAME);

        if (empty($updates)) {
            return null;
        }

        $this->handle(new FlowIndexingMessage(array_values($updates), null, $event->getContext()));

        return null;
    }

    public function handle(EntityIndexingMessage $message): void
    {
        $ids = array_unique(array_filter($message->getData()));

        if (empty($ids)) {
            return;
        }

        $this->payloadUpdater->update($ids);

        $this->eventDispatcher->dispatch(new FlowIndexerEvent($ids, $message->getContext()));
    }

    public function getTotal(): int
    {
        return $this->iteratorFactory->createIterator($this->repository->getDefinition())->fetchCount();
    }

    public function getDecorated(): EntityIndexer
    {
        throw new DecorationPatternException(static::class);
    }
}
