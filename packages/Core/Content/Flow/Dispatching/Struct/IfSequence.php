<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching\Struct;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal not intended for decoration or replacement
 */
#[Package('services-settings')]
class IfSequence extends Sequence
{
    public string $ruleId;

    public ?Sequence $falseCase = null;

    public ?Sequence $trueCase = null;
}
