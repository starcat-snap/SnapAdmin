<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate\Service\Event;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('services-settings')]
class AttachmentLoaderCriteriaEvent extends Event
{
    final public const EVENT_NAME = 'mail.after.create.message';

    public function __construct(private readonly Criteria $criteria)
    {
    }

    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }
}
