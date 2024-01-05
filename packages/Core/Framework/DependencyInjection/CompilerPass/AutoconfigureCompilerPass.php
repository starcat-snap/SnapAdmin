<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DependencyInjection\CompilerPass;

use League\Flysystem\FilesystemOperator;
use SnapAdmin\Core\Content\Document\Renderer\AbstractDocumentRenderer;
use SnapAdmin\Core\Content\Flow\Dispatching\Storer\FlowStorer;
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
            ->registerForAutoconfiguration(AbstractRouteScope::class)
            ->addTag('snap.route_scope');

        $container
            ->registerForAutoconfiguration(EntityExtension::class)
            ->addTag('snap.entity.extension');

        $container
            ->registerForAutoconfiguration(ScheduledTask::class)
            ->addTag('snap.scheduled.task');

        $container
            ->registerForAutoconfiguration(EntityIndexer::class)
            ->addTag('snap.entity_indexer');

        $container
            ->registerForAutoconfiguration(ExceptionHandlerInterface::class)
            ->addTag('snap.dal.exception_handler');
        $container
            ->registerForAutoconfiguration(AbstractValueGenerator::class)
            ->addTag('snap.value_generator_pattern');
        $container
            ->registerForAutoconfiguration(FieldSerializerInterface::class)
            ->addTag('snap.field_serializer');
        $container
            ->registerForAutoconfiguration(AbstractDocumentRenderer::class)
            ->addTag('document.renderer');
        $container
            ->registerForAutoconfiguration(TemplateNamespaceHierarchyBuilderInterface::class)
            ->addTag('snap.twig.hierarchy_builder');
        $container
            ->registerForAutoconfiguration(Rule::class)
            ->addTag('snap.rule.definition');
        $container
            ->registerForAutoconfiguration(FlowStorer::class)
            ->addTag('flow.storer');
        $container->registerAliasForArgument('snap.filesystem.private', FilesystemOperator::class, 'privateFilesystem');
        $container->registerAliasForArgument('snap.filesystem.public', FilesystemOperator::class, 'publicFilesystem');
    }
}
