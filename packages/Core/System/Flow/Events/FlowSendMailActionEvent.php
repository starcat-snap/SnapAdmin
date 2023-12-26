<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Events;

use SnapAdmin\Core\Content\MailTemplate\MailTemplateEntity;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\SnapAdminEvent;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Validation\DataBag\DataBag;
use SnapAdmin\Core\System\Flow\Dispatching\StorableFlow;

#[Package('services-settings')]
class FlowSendMailActionEvent implements SnapAdminEvent
{
    public function __construct(
        private readonly DataBag $dataBag,
        private readonly MailTemplateEntity $mailTemplate,
        private readonly StorableFlow $flow
    ) {
    }

    public function getContext(): Context
    {
        return $this->flow->getContext();
    }

    public function getDataBag(): DataBag
    {
        return $this->dataBag;
    }

    public function getMailTemplate(): MailTemplateEntity
    {
        return $this->mailTemplate;
    }

    public function getStorableFlow(): StorableFlow
    {
        return $this->flow;
    }
}
