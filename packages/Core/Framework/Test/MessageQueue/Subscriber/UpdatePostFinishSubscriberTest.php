<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\MessageQueue\Subscriber;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\Registry\TaskRegistry;
use SnapAdmin\Core\Framework\MessageQueue\Subscriber\UpdatePostFinishSubscriber;
use SnapAdmin\Core\Framework\Update\Event\UpdatePostFinishEvent;

/**
 * @internal
 */
#[Package('system-settings')]
class UpdatePostFinishSubscriberTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $events = UpdatePostFinishSubscriber::getSubscribedEvents();

        static::assertCount(1, $events);
        static::assertArrayHasKey(UpdatePostFinishEvent::class, $events);
        static::assertEquals('updatePostFinishEvent', $events[UpdatePostFinishEvent::class]);
    }

    public function testUpdatePostFinishEvent(): void
    {
        $registry = $this->createMock(TaskRegistry::class);
        $registry->expects(static::once())->method('registerTasks');

        (new UpdatePostFinishSubscriber($registry))->updatePostFinishEvent();
    }
}
