<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching\Struct;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal not intended for decoration or replacement
 */
#[Package('services-settings')]
class ActionSequence extends Sequence
{
    public string $action;

    public array $config = [];

    public ?Sequence $nextAction = null;

    public ?string $appFlowActionId = null;
}
