<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Log;

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

    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [];
    }
}
