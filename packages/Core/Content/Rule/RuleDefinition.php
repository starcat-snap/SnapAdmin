<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Rule;

use SnapAdmin\Core\Content\Flow\Aggregate\FlowSequence\FlowSequenceDefinition;
use SnapAdmin\Core\Content\Rule\Aggregate\RuleCondition\RuleConditionDefinition;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BlobField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RestrictDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RuleAreas;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\JsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ListField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class RuleDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'rule';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return RuleCollection::class;
    }

    public function getEntityClass(): string
    {
        return RuleEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('name', 'name'))->addFlags(new ApiAware(), new Required()),
            (new IntField('priority', 'priority'))->addFlags(new Required()),
            (new LongTextField('description', 'description'))->addFlags(new ApiAware()),
            (new BlobField('payload', 'payload'))->removeFlag(ApiAware::class)->addFlags(new WriteProtected(Context::SYSTEM_SCOPE)),
            (new BoolField('invalid', 'invalid'))->addFlags(new WriteProtected(Context::SYSTEM_SCOPE)),
            (new ListField('areas', 'areas'))->addFlags(new WriteProtected(Context::SYSTEM_SCOPE)),
            (new CustomFields())->addFlags(new ApiAware()),
            new JsonField('module_types', 'moduleTypes'),
            (new OneToManyAssociationField('flowSequences', FlowSequenceDefinition::class, 'rule_id', 'id'))->addFlags(new RestrictDelete(), new RuleAreas(RuleAreas::FLOW_AREA)),
            (new OneToManyAssociationField('conditions', RuleConditionDefinition::class, 'rule_id', 'id'))->addFlags(new CascadeDelete()),

        ]);
    }
}
