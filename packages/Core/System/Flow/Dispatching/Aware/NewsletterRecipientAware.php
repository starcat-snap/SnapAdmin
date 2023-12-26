<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching\Aware;

use SnapAdmin\Core\Framework\Event\IsFlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
#[IsFlowEventAware]
interface NewsletterRecipientAware
{
    public const NEWSLETTER_RECIPIENT_ID = 'newsletterRecipientId';

    public const NEWSLETTER_RECIPIENT = 'newsletterRecipient';

    public function getNewsletterRecipientId(): string;
}
