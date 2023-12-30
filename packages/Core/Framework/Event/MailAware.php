<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Event\EventData\MailRecipientStruct;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
#[IsFlowEventAware]
interface MailAware
{
    public const MAIL_STRUCT = 'mailStruct';

    public function getMailStruct(): MailRecipientStruct;
}
