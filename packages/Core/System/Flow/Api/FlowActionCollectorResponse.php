<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Api;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<FlowActionDefinition>
 */
#[Package('services-settings')]
class FlowActionCollectorResponse extends Collection
{
    protected function getExpectedClass(): ?string
    {
        return FlowActionDefinition::class;
    }
}
