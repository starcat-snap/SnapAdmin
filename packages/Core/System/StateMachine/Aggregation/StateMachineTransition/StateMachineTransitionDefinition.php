<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineTransition;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateDefinition;
use SnapAdmin\Core\System\StateMachine\StateMachineDefinition;

#[Package('system-settings')]
class StateMachineTransitionDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'state_machine_transition';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return StateMachineTransitionEntity::class;
    }

    public function getCollectionClass(): string
    {
        return StateMachineTransitionCollection::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            (new StringField('action_name', 'actionName'))->addFlags(new Required()),

            (new FkField('state_machine_id', 'stateMachineId', StateMachineDefinition::class))->addFlags(new Required()),
            new ManyToOneAssociationField('stateMachine', 'state_machine_id', StateMachineDefinition::class, 'id', false),

            (new FkField('from_state_id', 'fromStateId', StateMachineStateDefinition::class))->addFlags(new Required()),
            new ManyToOneAssociationField('fromStateMachineState', 'from_state_id', StateMachineStateDefinition::class, 'id', false),

            (new FkField('to_state_id', 'toStateId', StateMachineStateDefinition::class))->addFlags(new Required()),
            new ManyToOneAssociationField('toStateMachineState', 'to_state_id', StateMachineStateDefinition::class, 'id', false),
            new CustomFields(),
        ]);
    }
}
