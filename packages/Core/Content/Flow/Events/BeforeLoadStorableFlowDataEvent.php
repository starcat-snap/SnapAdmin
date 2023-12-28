<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Events;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Event\GenericEvent;
use SnapAdmin\Core\Framework\Event\SnapAdminEvent;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('services-settings')]
class BeforeLoadStorableFlowDataEvent extends Event implements SnapAdminEvent, GenericEvent
{
    public function __construct(
        private readonly string $entityName,
        private readonly Criteria $criteria,
        private readonly Context $context,
    ) {
    }

    public function getName(): string
    {
        return 'flow.storer.' . $this->entityName . '.criteria.event';
    }

    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}
