<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\TaxProvider;

use SnapAdmin\Core\Content\Rule\RuleDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RestrictDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\TaxProvider\Aggregate\TaxProviderTranslation\TaxProviderTranslationDefinition;

#[Package('checkout')]
class TaxProviderDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'tax_provider';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return TaxProviderCollection::class;
    }

    public function getEntityClass(): string
    {
        return TaxProviderEntity::class;
    }

    public function since(): ?string
    {
        return '6.5.0.0';
    }

    public function getDefaults(): array
    {
        return [
            'position' => 1,
            'active' => true,
        ];
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),
            (new StringField('identifier', 'identifier'))->addFlags(new Required()),
            (new BoolField('active', 'active'))->addFlags(new ApiAware()),
            (new TranslatedField('name'))->addFlags(new ApiAware()),
            (new BoolField('active', 'active'))->addFlags(new ApiAware()),
            (new IntField('priority', 'priority'))->addFlags(new Required(), new ApiAware()),
            (new StringField('process_url', 'processUrl'))->addFlags(new ApiAware()),
            new FkField('availability_rule_id', 'availabilityRuleId', RuleDefinition::class),
            (new TranslatedField('customFields'))->addFlags(new ApiAware()),
            new TranslationsAssociationField(TaxProviderTranslationDefinition::class, 'tax_provider_id'),
            (new ManyToOneAssociationField('availabilityRule', 'availability_rule_id', RuleDefinition::class))->addFlags(new RestrictDelete()),
        ]);
    }
}
