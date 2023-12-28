<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching\Storer;

use SnapAdmin\Core\Content\Flow\Dispatching\StorableFlow;
use SnapAdmin\Core\Framework\Event\FlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
abstract class FlowStorer
{
    /**
     * @param array<string, mixed> $stored
     *
     * @return array<string, mixed>
     */
    abstract public function store(FlowEventAware $event, array $stored): array;

    abstract public function restore(StorableFlow $storable): void;
}
