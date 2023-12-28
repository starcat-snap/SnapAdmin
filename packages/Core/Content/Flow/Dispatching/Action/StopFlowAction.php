<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching\Action;

use SnapAdmin\Core\Content\Flow\Dispatching\DelayableAction;
use SnapAdmin\Core\Content\Flow\Dispatching\StorableFlow;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('services-settings')]
class StopFlowAction extends FlowAction implements DelayableAction
{
    public static function getName(): string
    {
        return 'action.stop.flow';
    }

    /**
     * @return array<int, string|null>
     */
    public function requirements(): array
    {
        return [];
    }

    public function handleFlow(StorableFlow $flow): void
    {
        $flow->stop();
    }
}
