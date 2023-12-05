<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\MessageQueue\ScheduledTask;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<ScheduledTaskEntity>
 */
#[Package('core')]
class ScheduledTaskCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'dal_scheduled_task_collection';
    }

    protected function getExpectedClass(): string
    {
        return ScheduledTaskEntity::class;
    }
}
