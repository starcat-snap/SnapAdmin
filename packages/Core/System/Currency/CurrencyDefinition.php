<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Currency;

use SnapAdmin\Core\Checkout\Order\OrderDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionDiscountPrice\PromotionDiscountPriceDefinition;
use SnapAdmin\Core\Content\ProductExport\ProductExportDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CashRoundingConfigField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RestrictDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Runtime;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FloatField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Currency\Aggregate\CurrencyCountryRounding\CurrencyCountryRoundingDefinition;
use SnapAdmin\Core\System\Currency\Aggregate\CurrencyTranslation\CurrencyTranslationDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelCurrency\SalesChannelCurrencyDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainDefinition;
use SnapAdmin\Core\System\SalesChannel\SalesChannelDefinition;

#[Package('system')]
class CurrencyDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'currency';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CurrencyCollection::class;
    }

    public function getEntityClass(): string
    {
        return CurrencyEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),
            (new FloatField('factor', 'factor'))->addFlags(new ApiAware(), new Required()),
            (new StringField('symbol', 'symbol'))->addFlags(new ApiAware(), new Required()),
            (new StringField('iso_code', 'isoCode', 3))->addFlags(new ApiAware(), new Required()),
            (new TranslatedField('shortName'))->addFlags(new ApiAware(), new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new TranslatedField('name'))->addFlags(new ApiAware(), new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            (new IntField('position', 'position'))->addFlags(new ApiAware()),
            (new BoolField('is_system_default', 'isSystemDefault'))->addFlags(new ApiAware(), new Runtime()),
            (new FloatField('tax_free_from', 'taxFreeFrom'))->addFlags(new ApiAware()),
            (new TranslatedField('customFields'))->addFlags(new ApiAware()),
            (new TranslationsAssociationField(CurrencyTranslationDefinition::class, 'currency_id'))->addFlags(new Required()),
            (new OneToManyAssociationField('salesChannelDefaultAssignments', SalesChannelDefinition::class, 'currency_id', 'id'))->addFlags(new RestrictDelete()),
            (new OneToManyAssociationField('orders', OrderDefinition::class, 'currency_id', 'id'))->addFlags(new RestrictDelete()),
            new ManyToManyAssociationField('salesChannels', SalesChannelDefinition::class, SalesChannelCurrencyDefinition::class, 'currency_id', 'sales_channel_id'),
            (new OneToManyAssociationField('salesChannelDomains', SalesChannelDomainDefinition::class, 'currency_id'))->addFlags(new RestrictDelete()),
            (new OneToManyAssociationField('promotionDiscountPrices', PromotionDiscountPriceDefinition::class, 'currency_id', 'id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('productExports', ProductExportDefinition::class, 'currency_id', 'id'))->addFlags(new RestrictDelete()),
            (new CashRoundingConfigField('item_rounding', 'itemRounding'))->addFlags(new ApiAware(), new Required()),
            (new CashRoundingConfigField('total_rounding', 'totalRounding'))->addFlags(new ApiAware(), new Required()),
            (new OneToManyAssociationField('countryRoundings', CurrencyCountryRoundingDefinition::class, 'currency_id'))->addFlags(new CascadeDelete()),
        ]);
    }
}
