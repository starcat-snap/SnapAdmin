<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\EventData\EventDataCollection;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('business-ops')]
class FlowLogEvent extends Event implements FlowEventAware
{
    final public const NAME = 'flow.log';

    private readonly array $config;

    public function __construct(
        private readonly string $name,
        private readonly FlowEventAware $event,
        ?array $config = []
    ) {
        $this->config = $config ?? [];
    }

    public static function getAvailableData(): EventDataCollection
    {
        return new EventDataCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEvent(): FlowEventAware
    {
        return $this->event;
    }

    public function getContext(): Context
    {
        return $this->event->getContext();
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
