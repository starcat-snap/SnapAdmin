<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<FlowEntity>
 */
#[Package('services-settings')]
class FlowCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'flow_collection';
    }

    protected function getExpectedClass(): string
    {
        return FlowEntity::class;
    }
}
