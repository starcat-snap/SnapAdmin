<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Helper;

use SnapAdmin\Core\Checkout\Customer\Aggregate\CustomerAddress\CustomerAddressDefinition;
use SnapAdmin\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupDefinition;
use SnapAdmin\Core\Checkout\Customer\Aggregate\CustomerGroupRegistrationSalesChannel\CustomerGroupRegistrationSalesChannelDefinition;
use SnapAdmin\Core\Checkout\Customer\Aggregate\CustomerGroupTranslation\CustomerGroupTranslationDefinition;
use SnapAdmin\Core\Checkout\Customer\Aggregate\CustomerRecovery\CustomerRecoveryDefinition;
use SnapAdmin\Core\Checkout\Customer\Aggregate\CustomerTag\CustomerTagDefinition;
use SnapAdmin\Core\Checkout\Customer\CustomerDefinition;
use SnapAdmin\Core\Checkout\Document\Aggregate\DocumentBaseConfig\DocumentBaseConfigDefinition;
use SnapAdmin\Core\Checkout\Document\Aggregate\DocumentBaseConfigSalesChannel\DocumentBaseConfigSalesChannelDefinition;
use SnapAdmin\Core\Checkout\Document\Aggregate\DocumentType\DocumentTypeDefinition;
use SnapAdmin\Core\Checkout\Document\Aggregate\DocumentTypeTranslation\DocumentTypeTranslationDefinition;
use SnapAdmin\Core\Checkout\Document\DocumentDefinition;
use SnapAdmin\Core\Checkout\Order\Aggregate\OrderAddress\OrderAddressDefinition;
use SnapAdmin\Core\Checkout\Order\Aggregate\OrderCustomer\OrderCustomerDefinition;
use SnapAdmin\Core\Checkout\Order\Aggregate\OrderDelivery\OrderDeliveryDefinition;
use SnapAdmin\Core\Checkout\Order\Aggregate\OrderDeliveryPosition\OrderDeliveryPositionDefinition;
use SnapAdmin\Core\Checkout\Order\Aggregate\OrderLineItem\OrderLineItemDefinition;
use SnapAdmin\Core\Checkout\Order\Aggregate\OrderTag\OrderTagDefinition;
use SnapAdmin\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionDefinition;
use SnapAdmin\Core\Checkout\Order\OrderDefinition;
use SnapAdmin\Core\Checkout\Payment\Aggregate\PaymentMethodTranslation\PaymentMethodTranslationDefinition;
use SnapAdmin\Core\Checkout\Payment\PaymentMethodDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionCartRule\PromotionCartRuleDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionDiscount\PromotionDiscountDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionDiscountPrice\PromotionDiscountPriceDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionDiscountRule\PromotionDiscountRuleDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionIndividualCode\PromotionIndividualCodeDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionOrderRule\PromotionOrderRuleDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionPersonaCustomer\PromotionPersonaCustomerDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionPersonaRule\PromotionPersonaRuleDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionSalesChannel\PromotionSalesChannelDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionSetGroup\PromotionSetGroupDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionSetGroupRule\PromotionSetGroupRuleDefinition;
use SnapAdmin\Core\Checkout\Promotion\Aggregate\PromotionTranslation\PromotionTranslationDefinition;
use SnapAdmin\Core\Checkout\Promotion\PromotionDefinition;
use SnapAdmin\Core\Content\Category\Aggregate\CategoryTag\CategoryTagDefinition;
use SnapAdmin\Core\Content\Category\Aggregate\CategoryTranslation\CategoryTranslationDefinition;
use SnapAdmin\Core\Content\Category\CategoryDefinition;
use SnapAdmin\Core\Content\Cms\Aggregate\CmsBlock\CmsBlockDefinition;
use SnapAdmin\Core\Content\Cms\Aggregate\CmsPageTranslation\CmsPageTranslationDefinition;
use SnapAdmin\Core\Content\Cms\Aggregate\CmsSection\CmsSectionDefinition;
use SnapAdmin\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotDefinition;
use SnapAdmin\Core\Content\Cms\Aggregate\CmsSlotTranslation\CmsSlotTranslationDefinition;
use SnapAdmin\Core\Content\Cms\CmsPageDefinition;
use SnapAdmin\Core\Content\ImportExport\Aggregate\ImportExportFile\ImportExportFileDefinition;
use SnapAdmin\Core\Content\ImportExport\Aggregate\ImportExportLog\ImportExportLogDefinition;
use SnapAdmin\Core\Content\ImportExport\ImportExportProfileDefinition;
use SnapAdmin\Core\Content\ImportExport\ImportExportProfileTranslationDefinition;
use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailHeaderFooter\MailHeaderFooterDefinition;
use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailHeaderFooterTranslation\MailHeaderFooterTranslationDefinition;
use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateMedia\MailTemplateMediaDefinition;
use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateTranslation\MailTemplateTranslationDefinition;
use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateType\MailTemplateTypeDefinition;
use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateTypeTranslation\MailTemplateTypeTranslationDefinition;
use SnapAdmin\Core\Content\MailTemplate\MailTemplateDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaDefaultFolder\MediaDefaultFolderDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaFolder\MediaFolderDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaFolderConfiguration\MediaFolderConfigurationDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaFolderConfigurationMediaThumbnailSize\MediaFolderConfigurationMediaThumbnailSizeDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaTag\MediaTagDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnail\MediaThumbnailDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnailSize\MediaThumbnailSizeDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaTranslation\MediaTranslationDefinition;
use SnapAdmin\Core\Content\Media\MediaDefinition;
use SnapAdmin\Core\Content\Newsletter\Aggregate\NewsletterRecipient\NewsletterRecipientDefinition;
use SnapAdmin\Core\Content\Newsletter\Aggregate\NewsletterRecipientTag\NewsletterRecipientTagDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductCategory\ProductCategoryDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductCategoryTree\ProductCategoryTreeDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductConfiguratorSetting\ProductConfiguratorSettingDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductCrossSelling\ProductCrossSellingDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductCrossSellingAssignedProducts\ProductCrossSellingAssignedProductsDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductCrossSellingTranslation\ProductCrossSellingTranslationDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductCustomFieldSet\ProductCustomFieldSetDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductFeatureSet\ProductFeatureSetDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductFeatureSetTranslation\ProductFeatureSetTranslationDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductKeywordDictionary\ProductKeywordDictionaryDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductManufacturer\ProductManufacturerDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductManufacturerTranslation\ProductManufacturerTranslationDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductMedia\ProductMediaDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductOption\ProductOptionDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductPrice\ProductPriceDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductProperty\ProductPropertyDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductReview\ProductReviewDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductSearchKeyword\ProductSearchKeywordDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductTag\ProductTagDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductTranslation\ProductTranslationDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;
use SnapAdmin\Core\Content\Product\ProductDefinition;
use SnapAdmin\Core\Content\Product\SalesChannel\Sorting\ProductSortingDefinition;
use SnapAdmin\Core\Content\ProductExport\ProductExportDefinition;
use SnapAdmin\Core\Content\ProductStream\Aggregate\ProductStreamFilter\ProductStreamFilterDefinition;
use SnapAdmin\Core\Content\ProductStream\Aggregate\ProductStreamTranslation\ProductStreamTranslationDefinition;
use SnapAdmin\Core\Content\ProductStream\ProductStreamDefinition;
use SnapAdmin\Core\Content\Rule\Aggregate\RuleCondition\RuleConditionDefinition;
use SnapAdmin\Core\Content\Rule\RuleDefinition;
use SnapAdmin\Core\Content\Seo\SeoUrl\SeoUrlDefinition;
use SnapAdmin\Core\Content\Seo\SeoUrlTemplate\SeoUrlTemplateDefinition;
use SnapAdmin\Core\Framework\App\Aggregate\ActionButton\ActionButtonDefinition;
use SnapAdmin\Core\Framework\App\Aggregate\ActionButtonTranslation\ActionButtonTranslationDefinition;
use SnapAdmin\Core\Framework\App\Aggregate\AppTranslation\AppTranslationDefinition;
use SnapAdmin\Core\Framework\App\AppDefinition;
use SnapAdmin\Core\Framework\App\Template\TemplateDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Version\VersionDefinition;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Country\Aggregate\CountryState\CountryStateDefinition;
use SnapAdmin\Core\System\Country\CountryDefinition;
use SnapAdmin\Core\System\Currency\CurrencyDefinition;
use SnapAdmin\Core\System\CustomField\Aggregate\CustomFieldSet\CustomFieldSetDefinition;
use SnapAdmin\Core\System\CustomField\Aggregate\CustomFieldSetRelation\CustomFieldSetRelationDefinition;
use SnapAdmin\Core\System\CustomField\CustomFieldDefinition;
use SnapAdmin\Core\System\DeliveryTime\DeliveryTimeDefinition;
use SnapAdmin\Core\System\Integration\IntegrationDefinition;
use SnapAdmin\Core\System\Language\LanguageDefinition;
use SnapAdmin\Core\System\Locale\Aggregate\LocaleTranslation\LocaleTranslationDefinition;
use SnapAdmin\Core\System\Locale\LocaleDefinition;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeSalesChannel\NumberRangeSalesChannelDefinition;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeState\NumberRangeStateDefinition;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeType\NumberRangeTypeDefinition;
use SnapAdmin\Core\System\NumberRange\NumberRangeDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelAnalytics\SalesChannelAnalyticsDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelCountry\SalesChannelCountryDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelCurrency\SalesChannelCurrencyDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelLanguage\SalesChannelLanguageDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelPaymentMethod\SalesChannelPaymentMethodDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelShippingMethod\SalesChannelShippingMethodDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelTranslation\SalesChannelTranslationDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelType\SalesChannelTypeDefinition;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelTypeTranslation\SalesChannelTypeTranslationDefinition;
use SnapAdmin\Core\System\SalesChannel\SalesChannelDefinition;
use SnapAdmin\Core\System\Salutation\Aggregate\SalutationTranslation\SalutationTranslationDefinition;
use SnapAdmin\Core\System\Salutation\SalutationDefinition;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineHistory\StateMachineHistoryDefinition;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateDefinition;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateTranslationDefinition;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineTransition\StateMachineTransitionDefinition;
use SnapAdmin\Core\System\StateMachine\StateMachineDefinition;
use SnapAdmin\Core\System\StateMachine\StateMachineTranslationDefinition;
use SnapAdmin\Core\System\SystemConfig\SystemConfigDefinition;
use SnapAdmin\Core\System\Tag\TagDefinition;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRule\TaxRuleDefinition;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRuleType\TaxRuleTypeDefinition;
use SnapAdmin\Core\System\Tax\TaxDefinition;
use SnapAdmin\Core\System\Unit\UnitDefinition;
use SnapAdmin\Core\System\User\Aggregate\UserAccessKey\UserAccessKeyDefinition;
use SnapAdmin\Core\System\User\Aggregate\UserRecovery\UserRecoveryDefinition;
use SnapAdmin\Core\System\User\UserDefinition;

/**
 * @internal
 */
#[Package('services-settings')]
class PermissionCategorization
{
    private const CATEGORY_APP = 'app';
    private const CATEGORY_ADMIN_USER = 'admin_user';
    private const CATEGORY_CATEGORY = 'category';
    private const CATEGORY_CMS = 'cms';
    private const CATEGORY_CUSTOMER = 'customer';
    private const CATEGORY_CUSTOM_FIELDS = 'custom_fields';
    private const CATEGORY_DOCUMENTS = 'documents';
    private const CATEGORY_GOOGLE_SHOPPING = 'google_shopping';
    private const CATEGORY_IMPORT_EXPORT = 'import_export';
    private const CATEGORY_MAIL_TEMPLATES = 'mail_templates';
    private const CATEGORY_MEDIA = 'media';
    private const CATEGORY_NEWSLETTER = 'newsletter';
    private const CATEGORY_ORDER = 'order';
    private const CATEGORY_OTHER = 'other';
    private const CATEGORY_PAYMENT = 'payment';
    private const CATEGORY_PRODUCT = 'product';
    private const CATEGORY_PROMOTION = 'promotion';
    private const CATEGORY_RULES = 'rules';
    private const CATEGORY_SALES_CHANNEL = 'sales_channel';
    private const CATEGORY_SETTINGS = 'settings';
    private const CATEGORY_SOCIAL_SHOPPING = 'social_shopping';
    private const CATEGORY_TAG = 'tag';
    private const CATEGORY_THEME = 'theme';
    private const CATEGORY_ADDITIONAL_PRIVILEGES = 'additional_privileges';

    /**
     * @see \SnapAdmin\Storefront\Theme\ThemeDefinition::ENTITY_NAME
     */
    private const THEME_ENTITY_NAME = 'theme';
    /**
     * @see \SnapAdmin\Storefront\Theme\Aggregate\ThemeTranslationDefinition::ENTITY_NAME
     */
    private const THEME_TRANSLATION_ENTITY_NAME = 'theme_translation';
    /**
     * @see \SnapAdmin\Storefront\Theme\Aggregate\ThemeMediaDefinition::ENTITY_NAME
     */
    private const THEME_MEDIA_ENTITY_NAME = 'theme_media';
    /**
     * @see \SnapAdmin\Storefront\Theme\Aggregate\ThemeSalesChannelDefinition::ENTITY_NAME
     */
    private const THEME_SALES_CHANNEL_ENTITY_NAME = 'theme_sales_channel';

    private const PERMISSION_CATEGORIES = [
        self::CATEGORY_ADMIN_USER => [
            IntegrationDefinition::ENTITY_NAME,
            UserDefinition::ENTITY_NAME,
            UserAccessKeyDefinition::ENTITY_NAME,
            UserRecoveryDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_APP => [
            TemplateDefinition::ENTITY_NAME,
            AppDefinition::ENTITY_NAME,
            AppTranslationDefinition::ENTITY_NAME,
            ActionButtonDefinition::ENTITY_NAME,
            ActionButtonTranslationDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_CATEGORY => [
            CategoryDefinition::ENTITY_NAME,
            CategoryTranslationDefinition::ENTITY_NAME,
            CategoryTagDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_CMS => [
            CmsBlockDefinition::ENTITY_NAME,
            CmsPageDefinition::ENTITY_NAME,
            CmsPageTranslationDefinition::ENTITY_NAME,
            CmsSectionDefinition::ENTITY_NAME,
            CmsSlotDefinition::ENTITY_NAME,
            CmsSlotTranslationDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_CUSTOMER => [
            CustomerDefinition::ENTITY_NAME,
            CustomerAddressDefinition::ENTITY_NAME,
            CustomerGroupDefinition::ENTITY_NAME,
            CustomerGroupTranslationDefinition::ENTITY_NAME,
            CustomerGroupRegistrationSalesChannelDefinition::ENTITY_NAME,
            CustomerRecoveryDefinition::ENTITY_NAME,
            CustomerTagDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_CUSTOM_FIELDS => [
            CustomFieldDefinition::ENTITY_NAME,
            CustomFieldSetDefinition::ENTITY_NAME,
            CustomFieldSetRelationDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_DOCUMENTS => [
            DocumentDefinition::ENTITY_NAME,
            DocumentBaseConfigDefinition::ENTITY_NAME,
            DocumentBaseConfigSalesChannelDefinition::ENTITY_NAME,
            DocumentTypeDefinition::ENTITY_NAME,
            DocumentTypeTranslationDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_GOOGLE_SHOPPING => [
            'swag_google_shopping_account',
            'swag_google_shopping_ads_account',
            'swag_google_shopping_list_ads_account',
            'swag_google_shopping_category',
            'swag_google_shopping_merchant_account',
        ],
        self::CATEGORY_IMPORT_EXPORT => [
            ImportExportFileDefinition::ENTITY_NAME,
            ImportExportLogDefinition::ENTITY_NAME,
            ImportExportProfileDefinition::ENTITY_NAME,
            ImportExportProfileTranslationDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_MAIL_TEMPLATES => [
            MailHeaderFooterDefinition::ENTITY_NAME,
            MailHeaderFooterTranslationDefinition::ENTITY_NAME,
            MailTemplateDefinition::ENTITY_NAME,
            MailTemplateTranslationDefinition::ENTITY_NAME,
            MailTemplateMediaDefinition::ENTITY_NAME,
            MailTemplateTypeDefinition::ENTITY_NAME,
            MailTemplateTypeTranslationDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_MEDIA => [
            MediaDefinition::ENTITY_NAME,
            MediaTranslationDefinition::ENTITY_NAME,
            MediaDefaultFolderDefinition::ENTITY_NAME,
            MediaFolderDefinition::ENTITY_NAME,
            MediaFolderConfigurationDefinition::ENTITY_NAME,
            MediaFolderConfigurationMediaThumbnailSizeDefinition::ENTITY_NAME,
            MediaTagDefinition::ENTITY_NAME,
            MediaThumbnailDefinition::ENTITY_NAME,
            MediaThumbnailSizeDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_NEWSLETTER => [
            NewsletterRecipientDefinition::ENTITY_NAME,
            NewsletterRecipientTagDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_ORDER => [
            OrderDefinition::ENTITY_NAME,
            OrderAddressDefinition::ENTITY_NAME,
            OrderCustomerDefinition::ENTITY_NAME,
            OrderDeliveryDefinition::ENTITY_NAME,
            OrderDeliveryPositionDefinition::ENTITY_NAME,
            OrderLineItemDefinition::ENTITY_NAME,
            OrderTagDefinition::ENTITY_NAME,
            OrderTransactionDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_PAYMENT => [
            PaymentMethodDefinition::ENTITY_NAME,
            PaymentMethodTranslationDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_PRODUCT => [
            ProductDefinition::ENTITY_NAME,
            ProductCategoryDefinition::ENTITY_NAME,
            ProductCategoryTreeDefinition::ENTITY_NAME,
            ProductConfiguratorSettingDefinition::ENTITY_NAME,
            ProductCrossSellingDefinition::ENTITY_NAME,
            ProductCrossSellingAssignedProductsDefinition::ENTITY_NAME,
            ProductCrossSellingTranslationDefinition::ENTITY_NAME,
            ProductExportDefinition::ENTITY_NAME,
            ProductKeywordDictionaryDefinition::ENTITY_NAME,
            ProductManufacturerDefinition::ENTITY_NAME,
            ProductManufacturerTranslationDefinition::ENTITY_NAME,
            ProductMediaDefinition::ENTITY_NAME,
            ProductOptionDefinition::ENTITY_NAME,
            ProductPriceDefinition::ENTITY_NAME,
            ProductPropertyDefinition::ENTITY_NAME,
            ProductReviewDefinition::ENTITY_NAME,
            ProductSearchKeywordDefinition::ENTITY_NAME,
            ProductStreamDefinition::ENTITY_NAME,
            ProductStreamFilterDefinition::ENTITY_NAME,
            ProductStreamTranslationDefinition::ENTITY_NAME,
            ProductTagDefinition::ENTITY_NAME,
            ProductVisibilityDefinition::ENTITY_NAME,
            ProductSortingDefinition::ENTITY_NAME,
            ProductTranslationDefinition::ENTITY_NAME,
            ProductFeatureSetDefinition::ENTITY_NAME,
            ProductFeatureSetTranslationDefinition::ENTITY_NAME,
            ProductCustomFieldSetDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_PROMOTION => [
            PromotionDefinition::ENTITY_NAME,
            PromotionTranslationDefinition::ENTITY_NAME,
            PromotionCartRuleDefinition::ENTITY_NAME,
            PromotionDiscountDefinition::ENTITY_NAME,
            PromotionDiscountPriceDefinition::ENTITY_NAME,
            PromotionDiscountRuleDefinition::ENTITY_NAME,
            PromotionIndividualCodeDefinition::ENTITY_NAME,
            PromotionOrderRuleDefinition::ENTITY_NAME,
            PromotionPersonaCustomerDefinition::ENTITY_NAME,
            PromotionPersonaRuleDefinition::ENTITY_NAME,
            PromotionSalesChannelDefinition::ENTITY_NAME,
            PromotionSetGroupDefinition::ENTITY_NAME,
            PromotionSetGroupRuleDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_RULES => [
            RuleDefinition::ENTITY_NAME,
            RuleConditionDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_SALES_CHANNEL => [
            SalesChannelDefinition::ENTITY_NAME,
            SalesChannelAnalyticsDefinition::ENTITY_NAME,
            SalesChannelCountryDefinition::ENTITY_NAME,
            SalesChannelCurrencyDefinition::ENTITY_NAME,
            SalesChannelDomainDefinition::ENTITY_NAME,
            SalesChannelLanguageDefinition::ENTITY_NAME,
            SalesChannelPaymentMethodDefinition::ENTITY_NAME,
            SalesChannelShippingMethodDefinition::ENTITY_NAME,
            SalesChannelTranslationDefinition::ENTITY_NAME,
            SalesChannelTypeDefinition::ENTITY_NAME,
            SalesChannelTypeTranslationDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_SETTINGS => [
            CountryDefinition::ENTITY_NAME,
            CountryStateDefinition::ENTITY_NAME,
            CurrencyDefinition::ENTITY_NAME,
            DeliveryTimeDefinition::ENTITY_NAME,
            LanguageDefinition::ENTITY_NAME,
            LocaleDefinition::ENTITY_NAME,
            LocaleTranslationDefinition::ENTITY_NAME,
            NumberRangeDefinition::ENTITY_NAME,
            NumberRangeSalesChannelDefinition::ENTITY_NAME,
            NumberRangeStateDefinition::ENTITY_NAME,
            NumberRangeTypeDefinition::ENTITY_NAME,
            SalutationDefinition::ENTITY_NAME,
            SalutationTranslationDefinition::ENTITY_NAME,
            SeoUrlDefinition::ENTITY_NAME,
            SeoUrlTemplateDefinition::ENTITY_NAME,
            StateMachineDefinition::ENTITY_NAME,
            StateMachineHistoryDefinition::ENTITY_NAME,
            StateMachineStateDefinition::ENTITY_NAME,
            StateMachineStateTranslationDefinition::ENTITY_NAME,
            StateMachineTransitionDefinition::ENTITY_NAME,
            StateMachineTranslationDefinition::ENTITY_NAME,
            SystemConfigDefinition::ENTITY_NAME,
            TaxDefinition::ENTITY_NAME,
            TaxRuleDefinition::ENTITY_NAME,
            TaxRuleTypeDefinition::ENTITY_NAME,
            UnitDefinition::ENTITY_NAME,
            VersionDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_SOCIAL_SHOPPING => [
            'swag_social_shopping_sales_channel',
            'swag_social_shopping_product_error',
        ],
        self::CATEGORY_TAG => [
            TagDefinition::ENTITY_NAME,
        ],
        self::CATEGORY_THEME => [
            self::THEME_ENTITY_NAME,
            self::THEME_TRANSLATION_ENTITY_NAME,
            self::THEME_MEDIA_ENTITY_NAME,
            self::THEME_SALES_CHANNEL_ENTITY_NAME,
        ],
        self::CATEGORY_ADDITIONAL_PRIVILEGES => [
            'additional_privileges',
        ],
    ];

    public static function isInCategory(string $entity, string $category): bool
    {
        if ($category === self::CATEGORY_OTHER) {
            $allCategories = array_merge(...array_values(self::PERMISSION_CATEGORIES));

            return !\in_array($entity, $allCategories, true);
        }

        return \in_array($entity, self::PERMISSION_CATEGORIES[$category], true);
    }

    /**
     * @return string[]
     */
    public static function getCategoryNames(): array
    {
        $categories = array_keys(self::PERMISSION_CATEGORIES);
        $categories[] = self::CATEGORY_OTHER;

        return $categories;
    }
}
