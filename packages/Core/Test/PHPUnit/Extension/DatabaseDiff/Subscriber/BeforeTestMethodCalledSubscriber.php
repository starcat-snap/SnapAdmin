<?php declare(strict_types=1);

namespace SnapAdmin\Core\Test\PHPUnit\Extension\DatabaseDiff\Subscriber;

use PHPUnit\Event\Test\BeforeTestMethodCalled;
use PHPUnit\Event\Test\BeforeTestMethodCalledSubscriber as BeforeTestMethodCalledSubscriberInterface;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Test\PHPUnit\Extension\DatabaseDiff\DbState;

/**
 * @internal
 */
#[Package('core')]
class BeforeTestMethodCalledSubscriber implements BeforeTestMethodCalledSubscriberInterface
{
    public function __construct(private readonly DbState $dbState)
    {
    }

    public function notify(BeforeTestMethodCalled $event): void
    {
        $this->dbState->rememberCurrentDbState();
    }
}
