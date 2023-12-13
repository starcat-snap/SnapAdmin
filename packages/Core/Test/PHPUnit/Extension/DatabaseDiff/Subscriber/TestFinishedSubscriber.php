<?php declare(strict_types=1);

namespace SnapAdmin\Core\Test\PHPUnit\Extension\DatabaseDiff\Subscriber;

use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Test\PHPUnit\Extension\DatabaseDiff\DbState;

/**
 * @internal
 */
#[Package('core')]
class TestFinishedSubscriber implements FinishedSubscriber
{
    public function __construct(private readonly DbState $dbState)
    {
    }

    public function notify(Finished $event): void
    {
        $diff = $this->dbState->getDiff();

        if (!empty($diff)) {
            echo \PHP_EOL . $event->asString() . \PHP_EOL;

            print_r($diff);
        }
    }
}
