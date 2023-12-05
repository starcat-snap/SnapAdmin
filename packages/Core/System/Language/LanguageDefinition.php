<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Language;

use SnapAdmin\Core\Checkout\Customer\Aggregate\CustomerGroupTranslation\CustomerGroupTranslationDefinition;
use SnapAdmin\Core\Checkout\Customer\CustomerDefinition;
use SnapAdmin\Core\Checkout\Document\Aggregate\DocumentTypeTranslation\DocumentTypeTranslationDefinition;
use SnapAdmin\Core\Checkout\Order\OrderDefinition;
use SnapAdmin\Core\Checkout\Payment\Aggregate\PaymentMethodTranslation\PaymentMethodTranslationDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionTranslation\PromotionTranslationDefinition;
use SnapAdmin\Core\Checkout\Shipping\Aggregate\ShippingMethodTranslation\ShippingMethodTranslationDefinition;
use SnapAdmin\Core\Content\Category\Aggregate\CategoryTranslation\CategoryTranslationDefinition;
use SnapAdmin\Core\Content\Cms\Aggregate\CmsPageTranslation\CmsPageTranslationDefinition;
use SnapAdmin\Core\Content\Cms\Aggregate\CmsSlotTranslation\CmsSlotTranslationDefinition;
use SnapAdmin\Core\Content\ImportExport\ImportExportProfileTranslationDefinition;
use SnapAdmin\Core\Content\LandingPage\Aggregate\LandingPageTranslation\LandingPageTranslationDefinition;
use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailHeaderFooterTranslation\MailHeaderFooterTranslationDefinition;
use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateTranslation\MailTemplateTranslationDefinition;
use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateTypeTranslation\MailTemplateTypeTranslationDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaTranslation\MediaTranslationDefinition;
use SnapAdmin\Core\Content\Newsletter\Aggregate\NewsletterRecipient\NewsletterRecipientDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductCrossSellingTranslation\ProductCrossSellingTranslationDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductFeatureSetTranslation\ProductFeatureSetTranslationDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductKeywordDictionary\ProductKeywordDictionaryDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductManufacturerTranslation\ProductManufacturerTranslationDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductReview\ProductReviewDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductSearchConfig\ProductSearchConfigDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductSearchKeyword\ProductSearchKeywordDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductTranslation\ProductTranslationDefinition;
use SnapAdmin\Core\Content\Product\SalesChannel\Sorting\ProductSortingTranslationDefinition;
use SnapAdmin\Core\Content\ProductStream\Aggregate\ProductStreamTranslation\ProductStreamTranslationDefinition;
use SnapAdmin\Core\Content\Property\Aggregate\PropertyGroupOptionTranslation\PropertyGroupOptionTranslationDefinition;
use SnapAdmin\Core\Content\Property\Aggregate\PropertyGroupTranslation\PropertyGroupTranslationDefinition;
use SnapAdmin\Core\Content\Seo\SeoUrl\SeoUrlDefinition;
use SnapAdmin\Core\Framework\App\Aggregate\ActionButtonTranslation\ActionButtonTranslationDefinition;
use SnapAdmin\Core\Framework\App\Aggregate\AppScriptConditionTranslation\AppScriptConditionTranslationDefinition;
use SnapAdmin\Core\Framework\App\Aggregate\AppTranslation\AppTranslationDefinition;
use SnapAdmin\Core\Framework\App\Aggregate\CmsBlockTranslation\AppCmsBlockTranslationDefinition;
use SnapAdmin\Core\Framework\App\Aggregate\FlowActionTranslation\AppFlowActionTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ChildrenAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RestrictDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ParentAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ParentFkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Aggregate\PluginTranslation\PluginTranslationDefinition;
use SnapAdmin\Core\System\Country\Aggregate\CountryStateTranslation\CountryStateTranslationDefinition;
use SnapAdmin\Core\System\Country\Aggregate\CountryTranslation\CountryTranslationDefinition;
use SnapAdmin\Core\System\Currency\Aggregate\CurrencyTranslation\CurrencyTranslationDefinition;
use SnapAdmin\Core\System\DeliveryTime\Aggregate\DeliveryTimeTranslation\DeliveryTimeTranslationDefinition;
use SnapAdmin\Core\System\Locale\Aggregate\LocaleTranslation\LocaleTranslationDefinition;
use SnapAdmin\Core\System\Locale\LocaleDefinition;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeTranslation\NumberRangeTranslationDefinition;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeTypeTranslation\NumberRangeTypeTranslationDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelLanguage\SalesChannelLanguageDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelTranslation\SalesChannelTranslationDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelTypeTranslation\SalesChannelTypeTranslationDefinition;
use SnapAdmin\Core\System\SalesChannel\SalesChannelDefinition;
use SnapAdmin\Core\System\Salutation\Aggregate\SalutationTranslation\SalutationTranslationDefinition;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateTranslationDefinition;
use SnapAdmin\Core\System\StateMachine\StateMachineTranslationDefinition;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRuleTypeTranslation\TaxRuleTypeTranslationDefinition;
use SnapAdmin\Core\System\TaxProvider\Aggregate\TaxProviderTranslation\TaxProviderTranslationDefinition;
use SnapAdmin\Core\System\Unit\Aggregate\UnitTranslation\UnitTranslationDefinition;

#[Package('buyers-experience')]
class LanguageDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'language';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return LanguageCollection::class;
    }

    public function getEntityClass(): string
    {
        return LanguageEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        $collection = new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),
            (new ParentFkField(self::class))->addFlags(new ApiAware()),
            (new FkField('locale_id', 'localeId', LocaleDefinition::class))->addFlags(new ApiAware(), new Required()),
            (new FkField('translation_code_id', 'translationCodeId', LocaleDefinition::class))->addFlags(new ApiAware()),

            (new StringField('name', 'name'))->addFlags(new ApiAware(), new Required()),
            (new CustomFields())->addFlags(new ApiAware()),
            (new ParentAssociationField(self::class, 'id'))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('locale', 'locale_id', LocaleDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ManyToOneAssociationField('translationCode', 'translation_code_id', LocaleDefinition::class, 'id', false))->addFlags(new ApiAware()),
            (new ChildrenAssociationField(self::class))->addFlags(new ApiAware()),
            new ManyToManyAssociationField('salesChannels', SalesChannelDefinition::class, SalesChannelLanguageDefinition::class, 'language_id', 'sales_channel_id'),

            new OneToManyAssociationField('salesChannelDefaultAssignments', SalesChannelDefinition::class, 'language_id', 'id'),
            (new OneToManyAssociationField('salesChannelDomains', SalesChannelDomainDefinition::class, 'language_id'))->addFlags(new RestrictDelete()),
            (new OneToManyAssociationField('customers', CustomerDefinition::class, 'language_id'))->addFlags(new RestrictDelete()),
            (new OneToManyAssociationField('newsletterRecipients', NewsletterRecipientDefinition::class, 'language_id', 'id'))->addFlags(new RestrictDelete()),
            (new OneToManyAssociationField('orders', OrderDefinition::class, 'language_id', 'id'))->addFlags(new RestrictDelete()),

            // Translation Associations, not available over sales-channel-api
            (new OneToManyAssociationField('categoryTranslations', CategoryTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('countryStateTranslations', CountryStateTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('countryTranslations', CountryTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('currencyTranslations', CurrencyTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('customerGroupTranslations', CustomerGroupTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('localeTranslations', LocaleTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('mediaTranslations', MediaTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('paymentMethodTranslations', PaymentMethodTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('productManufacturerTranslations', ProductManufacturerTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('productTranslations', ProductTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('shippingMethodTranslations', ShippingMethodTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('unitTranslations', UnitTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('propertyGroupTranslations', PropertyGroupTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('propertyGroupOptionTranslations', PropertyGroupOptionTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('salesChannelTranslations', SalesChannelTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('salesChannelTypeTranslations', SalesChannelTypeTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('salutationTranslations', SalutationTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('pluginTranslations', PluginTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('productStreamTranslations', ProductStreamTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('stateMachineTranslations', StateMachineTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('stateMachineStateTranslations', StateMachineStateTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('cmsPageTranslations', CmsPageTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('cmsSlotTranslations', CmsSlotTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('mailTemplateTranslations', MailTemplateTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('mailHeaderFooterTranslations', MailHeaderFooterTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('documentTypeTranslations', DocumentTypeTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('numberRangeTypeTranslations', NumberRangeTypeTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('deliveryTimeTranslations', DeliveryTimeTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('productSearchKeywords', ProductSearchKeywordDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('productKeywordDictionaries', ProductKeywordDictionaryDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('mailTemplateTypeTranslations', MailTemplateTypeTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('promotionTranslations', PromotionTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('numberRangeTranslations', NumberRangeTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('productReviews', ProductReviewDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('seoUrlTranslations', SeoUrlDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('taxRuleTypeTranslations', TaxRuleTypeTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('productCrossSellingTranslations', ProductCrossSellingTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('importExportProfileTranslations', ImportExportProfileTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('productSortingTranslations', ProductSortingTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('productFeatureSetTranslations', ProductFeatureSetTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('appTranslations', AppTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('actionButtonTranslations', ActionButtonTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('landingPageTranslations', LandingPageTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('appCmsBlockTranslations', AppCmsBlockTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('appScriptConditionTranslations', AppScriptConditionTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToOneAssociationField('productSearchConfig', 'id', 'language_id', ProductSearchConfigDefinition::class, false))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('appFlowActionTranslations', AppFlowActionTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('taxProviderTranslations', TaxProviderTranslationDefinition::class, 'language_id'))->addFlags(new CascadeDelete()),
        ]);

        return $collection;
    }
}
