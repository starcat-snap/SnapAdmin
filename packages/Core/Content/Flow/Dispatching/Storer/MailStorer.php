<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching\Storer;

use SnapAdmin\Core\Content\Flow\Dispatching\StorableFlow;
use SnapAdmin\Core\Content\MailTemplate\Exception\MailEventConfigurationException;
use SnapAdmin\Core\Framework\Event\EventData\MailRecipientStruct;
use SnapAdmin\Core\Framework\Event\FlowEventAware;
use SnapAdmin\Core\Framework\Event\MailAware;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class MailStorer extends FlowStorer
{
    /**
     * @param array<string, mixed> $stored
     *
     * @return array<string, mixed>
     */
    public function store(FlowEventAware $event, array $stored): array
    {
        if (!$event instanceof MailAware) {
            return $stored;
        }

        if (!isset($stored[MailAware::MAIL_STRUCT])) {
            try {
                $mailStruct = $event->getMailStruct();
                $data = [
                    'recipients' => $mailStruct->getRecipients(),
                    'bcc' => $mailStruct->getBcc(),
                    'cc' => $mailStruct->getCc(),
                ];

                $stored[MailAware::MAIL_STRUCT] = $data;
            } catch (MailEventConfigurationException) {
            }
        }

        return $stored;
    }

    public function restore(StorableFlow $storable): void
    {
        if ($storable->hasStore(MailAware::MAIL_STRUCT)) {
            $this->restoreMailStore($storable);

            return;
        }
    }

    private function restoreMailStore(StorableFlow $storable): void
    {
        $mailStructData = $storable->getStore(MailAware::MAIL_STRUCT);

        $mailStruct = new MailRecipientStruct($mailStructData['recipients'] ?? []);
        $mailStruct->setBcc($mailStructData['bcc'] ?? null);
        $mailStruct->setCc($mailStructData['cc'] ?? null);

        $storable->setData(MailAware::MAIL_STRUCT, $mailStruct);
    }
}
