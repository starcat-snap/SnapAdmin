<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeState\NumberRangeStateDefinition;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeTranslation\NumberRangeTranslationDefinition;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeType\NumberRangeTypeDefinition;

#[Package('checkout')]
class NumberRangeDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'number_range';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return NumberRangeCollection::class;
    }

    public function getEntityClass(): string
    {
        return NumberRangeEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            (new FkField('type_id', 'typeId', NumberRangeTypeDefinition::class))->addFlags(new Required()),
            (new BoolField('global', 'global'))->addFlags(new Required()),
            new TranslatedField('name'),
            new TranslatedField('description'),
            (new StringField('pattern', 'pattern'))->addFlags(new Required()),
            (new IntField('start', 'start'))->addFlags(new Required()),
            new TranslatedField('customFields'),

            new ManyToOneAssociationField('type', 'type_id', NumberRangeTypeDefinition::class),
            (new TranslationsAssociationField(NumberRangeTranslationDefinition::class, 'number_range_id'))->addFlags(new Required()),
        ]);
    }
}
