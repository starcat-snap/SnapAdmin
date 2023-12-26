<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching\Storer;

use SnapAdmin\Core\Framework\Event\FlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Flow\Dispatching\Aware\MessageAware;
use SnapAdmin\Core\System\Flow\Dispatching\StorableFlow;

#[Package('services-settings')]
class MessageStorer extends FlowStorer
{
    /**
     * @param array<mixed> $stored
     *
     * @return array<string, mixed>
     */
    public function store(FlowEventAware $event, array $stored): array
    {
        if (!$event instanceof MessageAware || isset($stored[MessageAware::MESSAGE])) {
            return $stored;
        }

        $stored[MessageAware::MESSAGE] = \serialize($event->getMessage());

        return $stored;
    }

    public function restore(StorableFlow $storable): void
    {
        if (!$storable->hasStore(MessageAware::MESSAGE)) {
            return;
        }

        $mail = \unserialize($storable->getStore(MessageAware::MESSAGE));

        $storable->setData(MessageAware::MESSAGE, $mail);
    }
}
