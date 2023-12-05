<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<NestedEvent>
 */
#[Package('core')]
class NestedEventCollection extends Collection
{
    public function getFlatEventList(): self
    {
        $events = [];

        foreach ($this->getIterator() as $event) {
            foreach ($event->getFlatEventList() as $item) {
                $events[] = $item;
            }
        }

        return new self($events);
    }

    public function getApiAlias(): string
    {
        return 'dal_nested_event_collection';
    }

    protected function getExpectedClass(): ?string
    {
        return NestedEvent::class;
    }
}
