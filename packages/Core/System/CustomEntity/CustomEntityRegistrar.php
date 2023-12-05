<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEventFactory;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Read\EntityReaderInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\EntityAggregatorInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\EntitySearcherInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\VersionManager;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\CustomEntity\Schema\DynamicEntityDefinition;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @internal
 */
#[Package('core')]
class CustomEntityRegistrar
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function register(): void
    {
        if (!$this->container->get(Connection::class)->isConnected()) {
            return;
        }

        try {
            $entities = $this->container->get(Connection::class)->fetchAllAssociative('
                SELECT custom_entity.name, custom_entity.fields, custom_entity.flags
                FROM custom_entity
                    LEFT JOIN app ON app.id = custom_entity.app_id
                WHERE (custom_entity.app_id IS NULL OR app.active = 1) AND custom_entity.deleted_at IS NULL;
            ');
        } catch (Exception) {
            // kernel booted without database connection, or booted for migration and custom entity table not created yet
            return;
        }

        $definitions = [];
        $registry = $this->container->get(DefinitionInstanceRegistry::class);

        foreach ($entities as $entity) {
            $fields = json_decode((string)$entity['fields'], true, 512, \JSON_THROW_ON_ERROR);

            try {
                $flags = json_decode((string)$entity['flags'], true, 512, \JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $flags = [];
            }

            $definition = DynamicEntityDefinition::create($entity['name'], $fields, $flags, $this->container);

            $definitions[] = $definition;

            $this->container->set($definition->getEntityName(), $definition);
            $this->container->set($definition->getEntityName() . '.repository', $this->createRepository($definition));
            $registry->register($definition, $definition->getEntityName());
        }

        foreach ($definitions as $definition) {
            // triggers field generation to generate reverse foreign keys, translation definitions and mapping definitions
            $definition->getFields();
        }
    }

    private function createRepository(DynamicEntityDefinition $definition): EntityRepository
    {
        return new EntityRepository(
            $definition,
            $this->container->get(EntityReaderInterface::class),
            $this->container->get(VersionManager::class),
            $this->container->get(EntitySearcherInterface::class),
            $this->container->get(EntityAggregatorInterface::class),
            $this->container->get('event_dispatcher'),
            $this->container->get(EntityLoadedEventFactory::class)
        );
    }
}
