<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\Aggregate\TaxRule;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FloatField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\JsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ListField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Country\CountryDefinition;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRuleType\TaxRuleTypeDefinition;
use SnapAdmin\Core\System\Tax\TaxDefinition;

#[Package('checkout')]
class TaxRuleDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'tax_rule';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return TaxRuleCollection::class;
    }

    public function getEntityClass(): string
    {
        return TaxRuleEntity::class;
    }

    public function since(): ?string
    {
        return '6.1.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('tax_rule_type_id', 'taxRuleTypeId', TaxRuleTypeDefinition::class))->addFlags(new Required()),
            (new FkField('country_id', 'countryId', CountryDefinition::class))->addFlags(new Required()),
            (new FloatField('tax_rate', 'taxRate'))->addFlags(new Required(), new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            new JsonField('data', 'data', [
                new ListField('states', 'states'),
                new StringField('zipCode', 'zipCode'),
                new StringField('fromZipCode', 'fromZipCode'),
                new StringField('toZipCode', 'toZipCode'),
            ]),
            (new FkField('tax_id', 'taxId', TaxDefinition::class))->addFlags(new Required()),
            new DateTimeField('active_from', 'activeFrom'),
            new ManyToOneAssociationField('type', 'tax_rule_type_id', TaxRuleTypeDefinition::class, 'id'),
            new ManyToOneAssociationField('country', 'country_id', CountryDefinition::class, 'id'),
            new ManyToOneAssociationField('tax', 'tax_id', TaxDefinition::class, 'id'),
        ]);
    }
}
