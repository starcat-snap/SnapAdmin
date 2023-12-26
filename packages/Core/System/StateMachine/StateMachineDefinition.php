<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineHistory\StateMachineHistoryDefinition;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateDefinition;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineTransition\StateMachineTransitionDefinition;

#[Package('system-settings')]
class StateMachineDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'state_machine';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return StateMachineEntity::class;
    }

    public function getCollectionClass(): string
    {
        return StateMachineCollection::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            (new StringField('technical_name', 'technicalName'))->addFlags(new Required()),
            (new TranslatedField('name'))->addFlags(new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            new TranslatedField('customFields'),

            (new OneToManyAssociationField('states', StateMachineStateDefinition::class, 'state_machine_id'))->addFlags(new ApiAware(), new CascadeDelete()),
            (new OneToManyAssociationField('transitions', StateMachineTransitionDefinition::class, 'state_machine_id'))->addFlags(new ApiAware(), new CascadeDelete()),
            new FkField('initial_state_id', 'initialStateId', StateMachineStateDefinition::class),

            (new TranslationsAssociationField(StateMachineTranslationDefinition::class, 'state_machine_id'))->addFlags(new CascadeDelete(), new Required()),
            new OneToManyAssociationField('historyEntries', StateMachineHistoryDefinition::class, 'state_machine_id'),
        ]);
    }
}
