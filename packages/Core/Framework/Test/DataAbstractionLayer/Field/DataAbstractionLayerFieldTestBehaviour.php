<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityExtension;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEventFactory;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Read\EntityReaderInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\EntityAggregatorInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\EntitySearcherInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\VersionManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait DataAbstractionLayerFieldTestBehaviour
{
    abstract protected static function getContainer(): ContainerInterface;

    /**
     * @param class-string<EntityDefinition> ...$definitionClasses
     */
    protected function registerDefinition(string ...$definitionClasses): EntityDefinition
    {
        $ret = null;

        foreach ($definitionClasses as $definitionClass) {
            if ($this->getContainer()->has($definitionClass)) {
                /** @var EntityDefinition $definition */
                $definition = $this->getContainer()->get($definitionClass);
            } else {
                $definition = new $definitionClass();
                $this->getContainer()->get(DefinitionInstanceRegistry::class)->register($definition);

                if (!$this->getContainer()->has($definition->getEntityName() . '.repository')) {
                    $repository = new EntityRepository(
                        $definition,
                        $this->getContainer()->get(EntityReaderInterface::class),
                        $this->getContainer()->get(VersionManager::class),
                        $this->getContainer()->get(EntitySearcherInterface::class),
                        $this->getContainer()->get(EntityAggregatorInterface::class),
                        $this->getContainer()->get('event_dispatcher'),
                        $this->getContainer()->get(EntityLoadedEventFactory::class)
                    );

                    $this->getContainer()->set($definition->getEntityName() . '.repository', $repository);
                }
            }

            if ($ret === null) {
                $ret = $definition;
            }
        }

        if (!$ret) {
            throw new \InvalidArgumentException('Need at least one definition class to register.');
        }

        return $ret;
    }

    /**
     * @param class-string<EntityDefinition> $definitionClass
     * @param class-string<EntityExtension> ...$extensionsClasses
     */
    protected function registerDefinitionWithExtensions(string $definitionClass, string ...$extensionsClasses): EntityDefinition
    {
        $definition = $this->registerDefinition($definitionClass);
        foreach ($extensionsClasses as $extensionsClass) {
            if ($this->getContainer()->has($extensionsClass)) {
                /** @var EntityExtension $extension */
                $extension = $this->getContainer()->get($extensionsClass);
            } else {
                $extension = new $extensionsClass();
                $this->getContainer()->set($extensionsClass, $extension);
            }

            $definition->addExtension($extension);
        }

        return $definition;
    }

    protected function removeExtension(string ...$extensionsClasses): void
    {
        foreach ($extensionsClasses as $extensionsClass) {
            /** @var EntityExtension $extension */
            $extension = new $extensionsClass();
            if ($this->getContainer()->has($extension->getDefinitionClass())) {
                /** @var EntityDefinition $definition */
                $definition = $this->getContainer()->get($extension->getDefinitionClass());

                $definition->removeExtension($extension);

                $salesChannelDefinitionId = 'sales_channel_definition.' . $extension->getDefinitionClass();

                if ($this->getContainer()->has($salesChannelDefinitionId)) {
                    /** @var EntityDefinition $definition */
                    $definition = $this->getContainer()->get('sales_channel_definition.' . $extension->getDefinitionClass());

                    $definition->removeExtension($extension);
                }
            }
        }
    }
}
