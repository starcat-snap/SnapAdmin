<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching\Action;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Flow\Dispatching\DelayableAction;
use SnapAdmin\Core\System\Flow\Dispatching\StorableFlow;

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
