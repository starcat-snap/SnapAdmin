<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('system-settings')]
class StateMachineStateTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = 'state_machine_state_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return StateMachineStateTranslationEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function getParentDefinitionClass(): string
    {
        return StateMachineStateDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([(new StringField('name', 'name'))->addFlags(new Required()), new CustomFields()]);
    }
}
