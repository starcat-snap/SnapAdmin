<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class MailTemplateActions
{
    final public const MAIL_TEMPLATE_MAIL_SEND_ACTION = 'action.mail.send';
}
