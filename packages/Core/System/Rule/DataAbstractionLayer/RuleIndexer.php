<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Rule\DataAbstractionLayer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\Common\IteratorFactory;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexingMessage;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Exception\DecorationPatternException;
use SnapAdmin\Core\System\Rule\Event\RuleIndexerEvent;
use SnapAdmin\Core\System\Rule\RuleDefinition;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @final
 */
#[Package('services-settings')]
class RuleIndexer extends EntityIndexer
{
    final public const PAYLOAD_UPDATER = 'rule.payload';

    final public const AREA_UPDATER = 'rule.area';

    /**
     * @internal
     */
    public function __construct(
        private readonly IteratorFactory $iteratorFactory,
        private readonly EntityRepository $repository,
        private readonly RuleAreaUpdater $areaUpdater,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function getName(): string
    {
        return 'rule.indexer';
    }

    public function iterate(?array $offset): ?EntityIndexingMessage
    {
        $iterator = $this->iteratorFactory->createIterator($this->repository->getDefinition(), $offset);

        $ids = $iterator->fetch();

        if (empty($ids)) {
            return null;
        }

        return new RuleIndexingMessage(array_values($ids), $iterator->getOffset());
    }

    public function update(EntityWrittenContainerEvent $event): ?EntityIndexingMessage
    {
        $updates = $event->getPrimaryKeys(RuleDefinition::ENTITY_NAME);

        if (empty($updates)) {
            return null;
        }

        $this->handle(new RuleIndexingMessage(array_values($updates), null, $event->getContext()));

        return null;
    }

    public function handle(EntityIndexingMessage $message): void
    {
        $ids = $message->getData();

        $ids = array_unique(array_filter($ids));
        if (empty($ids)) {
            return;
        }

        if ($message->allow(self::AREA_UPDATER)) {
            $this->areaUpdater->update($ids);
        }

        $this->eventDispatcher->dispatch(new RuleIndexerEvent($ids, $message->getContext(), $message->getSkip()));
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
