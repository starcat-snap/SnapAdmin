<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Flow\Dispatching\Struct\Sequence;

#[Package('services-settings')]
class FlowState
{
    public string $flowId;

    public bool $stop = false;

    public Sequence $currentSequence;

    public bool $delayed = false;

    public function getSequenceId(): string
    {
        return $this->currentSequence->sequenceId;
    }
}
