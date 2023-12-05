<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event\EventData;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('business-ops')]
class EventDataCollection
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private array $data = [];

    public function add(string $name, EventDataType $type): self
    {
        $this->data[$name] = $type->toArray();

        return $this;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
