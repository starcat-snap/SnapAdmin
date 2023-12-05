<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DependencyInjection\CompilerPass;

use League\Flysystem\FilesystemOperator;
use SnapAdmin\Core\Checkout\Cart\CartDataCollectorInterface;
use SnapAdmin\Core\Checkout\Cart\CartProcessorInterface;
use SnapAdmin\Core\Checkout\Cart\CartValidatorInterface;
use SnapAdmin\Core\Checkout\Cart\LineItem\Group\LineItemGroupPackagerInterface;
use SnapAdmin\Core\Checkout\Cart\LineItem\Group\LineItemGroupSorterInterface;
use SnapAdmin\Core\Checkout\Cart\LineItemFactoryHandler\LineItemFactoryInterface;
use SnapAdmin\Core\Checkout\Cart\TaxProvider\AbstractTaxProvider;
use SnapAdmin\Core\Checkout\Customer\Password\LegacyEncoder\LegacyEncoderInterface;
use SnapAdmin\Core\Checkout\Document\Renderer\AbstractDocumentRenderer;
use SnapAdmin\Core\Checkout\Payment\Cart\PaymentHandler\SynchronousPaymentHandlerInterface;
use SnapAdmin\Core\Checkout\Promotion\Cart\Discount\Filter\FilterPickerInterface;
use SnapAdmin\Core\Checkout\Promotion\Cart\Discount\Filter\FilterSorterInterface;
use SnapAdmin\Core\Content\Cms\DataResolver\Element\CmsElementResolverInterface;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\FlowStorer;
use SnapAdmin\Core\Content\Product\SalesChannel\Listing\Filter\AbstractListingFilterHandler;
use SnapAdmin\Core\Content\Product\SalesChannel\Listing\Processor\AbstractListingProcessor;
use SnapAdmin\Core\Content\Seo\SeoUrlRoute\SeoUrlRouteInterface;
use SnapAdmin\Core\Content\Sitemap\Provider\AbstractUrlProvider;
use SnapAdmin\Core\Framework\Adapter\Filesystem\Adapter\AdapterFactoryInterface;
use SnapAdmin\Core\Framework\Adapter\Twig\NamespaceHierarchy\TemplateNamespaceHierarchyBuilderInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\ExceptionHandlerInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityExtension;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\FieldSerializerInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexer;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;
use SnapAdmin\Core\Framework\Routing\AbstractRouteScope;
use SnapAdmin\Core\Framework\Rule\Rule;
use SnapAdmin\Core\System\NumberRange\ValueGenerator\Pattern\AbstractValueGenerator;
use SnapAdmin\Core\System\SalesChannel\SalesChannelDefinition;
use SnapAdmin\Core\System\Tax\TaxRuleType\TaxRuleTypeFilterInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[Package('core')]
class AutoconfigureCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container
            ->registerForAutoconfiguration(EntityDefinition::class)
            ->addTag('snap.entity.definition');

        $container
            ->registerForAutoconfiguration(SalesChannelDefinition::class)
            ->addTag('snap.sales_channel.entity.definition');

        $container
            ->registerForAutoconfiguration(AbstractRouteScope::class)
            ->addTag('snap.route_scope');

        $container
            ->registerForAutoconfiguration(EntityExtension::class)
            ->addTag('snap.entity.extension');

        $container
            ->registerForAutoconfiguration(CartProcessorInterface::class)
            ->addTag('snap.cart.processor');

        $container
            ->registerForAutoconfiguration(CartDataCollectorInterface::class)
            ->addTag('snap.cart.collector');

        $container
            ->registerForAutoconfiguration(ScheduledTask::class)
            ->addTag('snap.scheduled.task');

        $container
            ->registerForAutoconfiguration(CartValidatorInterface::class)
            ->addTag('snap.cart.validator');

        $container
            ->registerForAutoconfiguration(LineItemFactoryInterface::class)
            ->addTag('snap.cart.line_item.factory');

        $container
            ->registerForAutoconfiguration(LineItemGroupPackagerInterface::class)
            ->addTag('lineitem.group.packager');

        $container
            ->registerForAutoconfiguration(LineItemGroupSorterInterface::class)
            ->addTag('lineitem.group.sorter');

        $container
            ->registerForAutoconfiguration(LegacyEncoderInterface::class)
            ->addTag('snap.legacy_encoder');

        $container
            ->registerForAutoconfiguration(EntityIndexer::class)
            ->addTag('snap.entity_indexer');

        $container
            ->registerForAutoconfiguration(ExceptionHandlerInterface::class)
            ->addTag('snap.dal.exception_handler');

        $container
            ->registerForAutoconfiguration(AbstractDocumentRenderer::class)
            ->addTag('document.renderer');

        $container
            ->registerForAutoconfiguration(SynchronousPaymentHandlerInterface::class)
            ->addTag('snap.payment.method.sync');

        $container
            ->registerForAutoconfiguration(FilterSorterInterface::class)
            ->addTag('promotion.filter.sorter');

        $container
            ->registerForAutoconfiguration(FilterPickerInterface::class)
            ->addTag('promotion.filter.picker');

        $container
            ->registerForAutoconfiguration(Rule::class)
            ->addTag('snap.rule.definition');

        $container
            ->registerForAutoconfiguration(AbstractTaxProvider::class)
            ->addTag('snap.tax.provider');

        $container
            ->registerForAutoconfiguration(CmsElementResolverInterface::class)
            ->addTag('snap.cms.data_resolver');

        $container
            ->registerForAutoconfiguration(FieldSerializerInterface::class)
            ->addTag('snap.field_serializer');

        $container
            ->registerForAutoconfiguration(FlowStorer::class)
            ->addTag('flow.storer');

        $container
            ->registerForAutoconfiguration(AbstractUrlProvider::class)
            ->addTag('snap.sitemap_url_provider');

        $container
            ->registerForAutoconfiguration(AdapterFactoryInterface::class)
            ->addTag('snap.filesystem.factory');

        $container
            ->registerForAutoconfiguration(AbstractValueGenerator::class)
            ->addTag('snap.value_generator_pattern');

        $container
            ->registerForAutoconfiguration(TaxRuleTypeFilterInterface::class)
            ->addTag('tax.rule_type_filter');

        $container
            ->registerForAutoconfiguration(SeoUrlRouteInterface::class)
            ->addTag('snap.seo_url.route');

        $container
            ->registerForAutoconfiguration(TemplateNamespaceHierarchyBuilderInterface::class)
            ->addTag('snap.twig.hierarchy_builder');

        $container
            ->registerForAutoconfiguration(AbstractListingProcessor::class)
            ->addTag('snap.listing.processor');

        $container
            ->registerForAutoconfiguration(AbstractListingFilterHandler::class)
            ->addTag('snap.listing.filter.handler');

        $container->registerAliasForArgument('snap.filesystem.private', FilesystemOperator::class, 'privateFilesystem');
        $container->registerAliasForArgument('snap.filesystem.public', FilesystemOperator::class, 'publicFilesystem');
    }
}
