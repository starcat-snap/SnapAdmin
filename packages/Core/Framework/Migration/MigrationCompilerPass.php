<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Migration;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

#[Package('core')]
class MigrationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $migrationCollections = $container->get(MigrationCollectionLoader::class)->collectAll();

        $activeCollectionMigrations = [[]];
        foreach ($migrationCollections as $collection) {
            $activeCollectionMigrations[] = $collection->getActiveMigrationTimestamps();
        }

        $container->setParameter('migration.active', array_merge(...$activeCollectionMigrations));
    }
}
