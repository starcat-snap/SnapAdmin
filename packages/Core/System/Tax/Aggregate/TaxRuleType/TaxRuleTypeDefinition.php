<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\Aggregate\TaxRuleType;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RestrictDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRule\TaxRuleDefinition;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRuleTypeTranslation\TaxRuleTypeTranslationDefinition;

#[Package('checkout')]
class TaxRuleTypeDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'tax_rule_type';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return TaxRuleTypeCollection::class;
    }

    public function getEntityClass(): string
    {
        return TaxRuleTypeEntity::class;
    }

    public function since(): ?string
    {
        return '6.1.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('technical_name', 'technicalName'))->addFlags(new Required(), new WriteProtected()),
            (new IntField('position', 'position'))->addFlags(new Required()),
            (new TranslatedField('typeName'))->addFlags(new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            (new OneToManyAssociationField('rules', TaxRuleDefinition::class, 'tax_rule_type_id'))->addFlags(new RestrictDelete()),
            (new TranslationsAssociationField(TaxRuleTypeTranslationDefinition::class, 'tax_rule_type_id'))->addFlags(new Required()),
        ]);
    }
}
