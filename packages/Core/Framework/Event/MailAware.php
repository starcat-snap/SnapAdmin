<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Event\EventData\MailRecipientStruct;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @deprecated tag:v6.6.0 - reason:class-hierarchy-change - extends of FlowEventAware will be removed, implement the interface inside your event
 */
#[Package('business-ops')]
interface MailAware extends FlowEventAware
{
    public const MAIL_STRUCT = 'mailStruct';

    public const SALES_CHANNEL_ID = 'salesChannelId';

    public function getMailStruct(): MailRecipientStruct;

    public function getSalesChannelId(): ?string;
}
