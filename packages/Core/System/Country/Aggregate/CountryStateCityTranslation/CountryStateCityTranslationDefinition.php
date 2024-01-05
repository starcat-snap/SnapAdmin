<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country\Aggregate\CountryStateCityTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Country\Aggregate\CountryState\CountryStateDefinition;
use SnapAdmin\Core\System\Country\Aggregate\CountryStateCity\CountryStateCityDefinition;

#[Package('system')]
class CountryStateCityTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = 'country_state_city_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CountryStateCityTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return CountryStateCityTranslationEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function getParentDefinitionClass(): string
    {
        return CountryStateCityDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new ApiAware(), new Required()),
            (new CustomFields())->addFlags(new ApiAware()),
        ]);
    }
}
