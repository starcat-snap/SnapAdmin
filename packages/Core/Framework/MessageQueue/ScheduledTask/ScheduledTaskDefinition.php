<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\MessageQueue\ScheduledTask;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class ScheduledTaskDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'scheduled_task';

    final public const STATUS_SCHEDULED = 'scheduled';

    final public const STATUS_QUEUED = 'queued';

    final public const STATUS_SKIPPED = 'skipped';

    final public const STATUS_RUNNING = 'running';

    final public const STATUS_FAILED = 'failed';

    final public const STATUS_INACTIVE = 'inactive';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ScheduledTaskCollection::class;
    }

    public function getEntityClass(): string
    {
        return ScheduledTaskEntity::class;
    }

    public function getDefaults(): array
    {
        return ['nextExecutionTime' => new \DateTime()];
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('scheduled_task_class', 'scheduledTaskClass', 512))->addFlags(new Required()),
            (new IntField('run_interval', 'runInterval', 0))->addFlags(new Required()),
            (new IntField('default_run_interval', 'defaultRunInterval', 0))->addFlags(new Required()),
            (new StringField('status', 'status'))->addFlags(new Required()),
            new DateTimeField('last_execution_time', 'lastExecutionTime'),
            (new DateTimeField('next_execution_time', 'nextExecutionTime'))->addFlags(new Required()),
        ]);
    }
}
