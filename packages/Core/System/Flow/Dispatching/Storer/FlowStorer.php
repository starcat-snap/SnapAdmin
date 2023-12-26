<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching\Storer;

use SnapAdmin\Core\Framework\Event\FlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Flow\Dispatching\StorableFlow;

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
