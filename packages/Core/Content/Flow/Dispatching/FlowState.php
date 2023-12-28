<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching;

use SnapAdmin\Core\Content\Flow\Dispatching\Struct\Sequence;
use SnapAdmin\Core\Framework\Log\Package;

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
