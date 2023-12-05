<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<BusinessEventDefinition>
 */
#[Package('business-ops')]
class BusinessEventCollectorResponse extends Collection
{
    protected function getExpectedClass(): ?string
    {
        return BusinessEventDefinition::class;
    }
}
