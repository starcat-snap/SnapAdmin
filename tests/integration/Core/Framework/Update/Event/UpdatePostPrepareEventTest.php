<?php declare(strict_types=1);

namespace SnapAdmin\Tests\Integration\Core\Framework\Update\Event;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Update\Event\UpdatePostPrepareEvent;

class UpdatePostPrepareEventTest extends TestCase
{
    public function testGetters(): void
    {
        $context = Context::createDefaultContext();
        $event = new UpdatePostPrepareEvent($context, 'currentVersion', 'newVersion');

        static::assertSame('currentVersion', $event->getCurrentVersion());
        static::assertSame('newVersion', $event->getNewVersion());
        static::assertSame($context, $event->getContext());
    }
}
