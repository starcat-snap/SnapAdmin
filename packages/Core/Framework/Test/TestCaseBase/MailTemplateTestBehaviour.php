<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\TestCaseBase;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Event\EventData\MailRecipientStruct;
use SnapAdmin\Core\Framework\Event\MailAware;
use SnapAdmin\Core\Framework\Event\SnapAdminEvent;
use SnapAdmin\Frontend\Channel\ChannelContext;

trait MailTemplateTestBehaviour
{
    use EventDispatcherBehaviour;

    /**
     * @param class-string<object> $expectedClass
     */
    public static function assertMailEvent(
        string              $expectedClass,
        SnapAdminEvent      $event,
        ChannelContext $channelContext
    ): void
    {
        TestCase::assertInstanceOf($expectedClass, $event);
        TestCase::assertSame($channelContext->getContext(), $event->getContext());
    }

    public static function assertMailRecipientStructEvent(MailRecipientStruct $expectedStruct, MailAware $event): void
    {
        TestCase::assertSame($expectedStruct->getRecipients(), $event->getMailStruct()->getRecipients());
    }

    protected function catchEvent(string $eventName, ?object &$eventResult): void
    {
        $eventDispatcher = $this->getContainer()->get('event_dispatcher');
        $this->addEventListener($eventDispatcher, $eventName, static function ($event) use (&$eventResult): void {
            $eventResult = $event;
        });
    }
}
