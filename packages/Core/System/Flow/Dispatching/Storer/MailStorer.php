<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching\Storer;

use SnapAdmin\Core\Checkout\Customer\CustomerEntity;
use SnapAdmin\Core\Checkout\Order\OrderEntity;
use SnapAdmin\Core\Content\MailTemplate\Exception\MailEventConfigurationException;
use SnapAdmin\Core\Framework\Event\CustomerAware;
use SnapAdmin\Core\Framework\Event\EventData\MailRecipientStruct;
use SnapAdmin\Core\Framework\Event\FlowEventAware;
use SnapAdmin\Core\Framework\Event\MailAware;
use SnapAdmin\Core\Framework\Event\OrderAware;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Flow\Dispatching\StorableFlow;

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

        if (isset($stored[MailAware::SALES_CHANNEL_ID])) {
            return $stored;
        }

        $stored[MailAware::SALES_CHANNEL_ID] = $event->getSalesChannelId();

        return $stored;
    }

    public function restore(StorableFlow $storable): void
    {
        if ($storable->hasStore(MailAware::MAIL_STRUCT)) {
            $this->restoreMailStore($storable);

            return;
        }

        if ($storable->hasData(OrderAware::ORDER)) {
            $this->restoreOrderData($storable);

            return;
        }

        if ($storable->hasData(CustomerAware::CUSTOMER)) {
            $this->restoreCustomerData($storable);
        }
    }

    private function restoreMailStore(StorableFlow $storable): void
    {
        $mailStructData = $storable->getStore(MailAware::MAIL_STRUCT);

        $mailStruct = new MailRecipientStruct($mailStructData['recipients'] ?? []);
        $mailStruct->setBcc($mailStructData['bcc'] ?? null);
        $mailStruct->setCc($mailStructData['cc'] ?? null);

        $storable->setData(MailAware::SALES_CHANNEL_ID, $storable->getStore(MailAware::SALES_CHANNEL_ID));
        $storable->setData(MailAware::MAIL_STRUCT, $mailStruct);
    }

    private function restoreOrderData(StorableFlow $storable): void
    {
        /** @var OrderEntity|null $order */
        $order = $storable->getData(OrderAware::ORDER);
        if (!$order) {
            return;
        }

        $customer = $order->getOrderCustomer();
        if (!$customer) {
            return;
        }

        $storable->setData(MailAware::SALES_CHANNEL_ID, $order->getSalesChannelId());
        $mailStruct = new MailRecipientStruct([$customer->getEmail() => $customer->getFirstName() . $customer->getLastName()]);
        $storable->setData(MailAware::MAIL_STRUCT, $mailStruct);
    }

    private function restoreCustomerData(StorableFlow $storable): void
    {
        /** @var CustomerEntity|null $customer */
        $customer = $storable->getData(CustomerAware::CUSTOMER);
        if (!$customer) {
            return;
        }

        $storable->setData(MailAware::SALES_CHANNEL_ID, $customer->getSalesChannelId());
        $mailStruct = new MailRecipientStruct([$customer->getEmail() => $customer->getFirstName() . $customer->getLastName()]);
        $storable->setData(MailAware::MAIL_STRUCT, $mailStruct);
    }
}
