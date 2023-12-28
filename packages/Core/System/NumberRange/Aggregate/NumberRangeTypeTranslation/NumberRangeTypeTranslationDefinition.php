<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeTypeTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeType\NumberRangeTypeDefinition;

#[Package('system-settings')]
class NumberRangeTypeTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = 'number_range_type_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return NumberRangeTypeTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return NumberRangeTypeTranslationEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function getParentDefinitionClass(): string
    {
        return NumberRangeTypeDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('type_name', 'typeName'))->addFlags(new Required()),
            new CustomFields(),
        ]);
    }
}
