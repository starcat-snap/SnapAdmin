<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\JsonSerializableTrait;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('core')]
abstract class NestedEvent extends Event implements SnapAdminEvent
{
    use JsonSerializableTrait;

    public function getEvents(): ?NestedEventCollection
    {
        return null;
    }

    public function getFlatEventList(): NestedEventCollection
    {
        $events = [$this];

        if (!$nestedEvents = $this->getEvents()) {
            return new NestedEventCollection($events);
        }

        foreach ($nestedEvents as $event) {
            $events[] = $event;
            foreach ($event->getFlatEventList() as $item) {
                $events[] = $item;
            }
        }

        return new NestedEventCollection($events);
    }
}
