<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country\Aggregate\CountryTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\JsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Country\CountryDefinition;

#[Package('system')]
class CountryTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = 'country_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CountryTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return CountryTranslationEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    public function getDefaults(): array
    {
        return [
            'addressFormat' => CountryDefinition::DEFAULT_ADDRESS_FORMAT,
        ];
    }

    protected function getParentDefinitionClass(): string
    {
        return CountryDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new ApiAware(), new Required()),
            (new JsonField('address_format', 'addressFormat'))->addFlags(new ApiAware(), new Required()),
            (new CustomFields())->addFlags(new ApiAware()),
        ]);
    }
}
