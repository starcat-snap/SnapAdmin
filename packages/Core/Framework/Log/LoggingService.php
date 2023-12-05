<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Log;

use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @internal
 */
#[Package('core')]
class LoggingService implements EventSubscriberInterface
{
    /**
     * @internal
     */
    public function __construct(
        private readonly string $environment,
        private readonly Logger $logger
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [];
    }
}
