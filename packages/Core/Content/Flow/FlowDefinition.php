<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow;

use SnapAdmin\Core\Content\Flow\Aggregate\FlowSequence\FlowSequenceDefinition;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BlobField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class FlowDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'flow';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return FlowCollection::class;
    }

    public function getEntityClass(): string
    {
        return FlowEntity::class;
    }

    public function getDefaults(): array
    {
        return [
            'active' => false,
            'priority' => 1,
        ];
    }

    public function since(): ?string
    {
        return '6.4.6.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('name', 'name', 255))->addFlags(new Required()),
            (new StringField('event_name', 'eventName', 255))->addFlags(new Required()),
            new IntField('priority', 'priority'),
            (new BlobField('payload', 'payload'))->removeFlag(ApiAware::class)->addFlags(new WriteProtected(Context::SYSTEM_SCOPE)),
            (new BoolField('invalid', 'invalid'))->addFlags(new WriteProtected(Context::SYSTEM_SCOPE)),
            new BoolField('active', 'active'),
            new StringField('description', 'description', 500),
            (new OneToManyAssociationField('sequences', FlowSequenceDefinition::class, 'flow_id', 'id'))->addFlags(new CascadeDelete()),
            new CustomFields(),
        ]);
    }
}
