<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class MailTemplateTypes
{
    final public const MAILTYPE_USER_RECOVERY_REQUEST = 'user.recovery.request';
}
