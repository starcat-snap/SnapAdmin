<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country\Aggregate\CountryStateCityDistrictTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;

class CountryStateCityDistrictTranslationDefinition extends EntityTranslationDefinition

{
    final public const ENTITY_NAME = 'country_state_city_district_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CountryStateCityDistrictTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return CountryStateCityDistrictTranslationEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new ApiAware(), new Required()),
            (new CustomFields())->addFlags(new ApiAware()),
        ]);
    }
}
