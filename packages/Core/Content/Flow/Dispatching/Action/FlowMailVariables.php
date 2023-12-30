<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching\Action;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
final class FlowMailVariables
{
    public const URL = 'url';
    public const TEMPLATE_DATA = 'templateData';
    public const SUBJECT = 'subject';
    public const REVIEW_FORM_DATA = 'reviewFormData';
    public const RESET_URL = 'resetUrl';
    public const RECIPIENTS = 'recipients';
    public const EVENT_NAME = 'name';
    public const MEDIA_ID = 'mediaId';
    public const EMAIL = 'email';

    public const CONTENTS = 'contents';

    public const DATA = 'data';
}
