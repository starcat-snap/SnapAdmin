<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Aggregate\FlowSequence;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<FlowSequenceEntity>
 */
#[Package('services-settings')]
class FlowSequenceCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'flow_sequence_collection';
    }

    protected function getExpectedClass(): string
    {
        return FlowSequenceEntity::class;
    }
}
