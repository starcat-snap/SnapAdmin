<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Currency\Aggregate\CurrencyTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Currency\CurrencyDefinition;

#[Package('system')]
class CurrencyTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = 'currency_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CurrencyTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return CurrencyTranslationEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function getParentDefinitionClass(): string
    {
        return CurrencyDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('short_name', 'shortName'))->addFlags(new ApiAware(), new Required()),
            (new StringField('name', 'name'))->addFlags(new ApiAware(), new Required()),
            (new CustomFields())->addFlags(new ApiAware()),
        ]);
    }
}
