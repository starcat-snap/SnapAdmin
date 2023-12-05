<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\MessageQueue\fixtures;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @internal
 */
#[AsMessageHandler]
final class TestMessageHandler
{
    public function __invoke(FooMessage|BarMessage $msg): void
    {
    }
}
