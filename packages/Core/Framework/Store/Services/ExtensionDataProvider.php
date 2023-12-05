<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Services;

use SnapAdmin\Core\Framework\App\AppCollection;
use SnapAdmin\Core\Framework\App\AppEntity;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Exception\DecorationPatternException;
use SnapAdmin\Core\Framework\Plugin\PluginCollection;
use SnapAdmin\Core\Framework\Store\StoreException;
use SnapAdmin\Core\Framework\Store\Struct\ExtensionCollection;

/**
 * @internal
 */
#[Package('services-settings')]
class ExtensionDataProvider extends AbstractExtensionDataProvider
{
    final public const HEADER_NAME_TOTAL_COUNT = 'SW-Meta-Total';

    public function __construct(
        private readonly ExtensionLoader $extensionLoader,
        private readonly EntityRepository $appRepository,
        private readonly EntityRepository $pluginRepository,
        private readonly ExtensionListingLoader $extensionListingLoader
    ) {
    }

    public function getInstalledExtensions(Context $context, bool $loadCloudExtensions = true, ?Criteria $searchCriteria = null): ExtensionCollection
    {
        $criteria = $searchCriteria ?: new Criteria();
        $criteria->addAssociation('translations');

        /** @var AppCollection $installedApps */
        $installedApps = $this->appRepository->search($criteria, $context)->getEntities();

        /** @var PluginCollection $installedPlugins */
        $installedPlugins = $this->pluginRepository->search($criteria, $context)->getEntities();
        $pluginCollection = $this->extensionLoader->loadFromPluginCollection($context, $installedPlugins);

        $localExtensions = $this->extensionLoader->loadFromAppCollection($context, $installedApps)->merge($pluginCollection);

        if ($loadCloudExtensions) {
            return $this->extensionListingLoader->load($localExtensions, $context);
        }

        return $localExtensions;
    }

    public function getAppEntityFromTechnicalName(string $technicalName, Context $context): AppEntity
    {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('name', $technicalName));
        $app = $this->appRepository->search($criteria, $context)->getEntities()->first();

        if (!$app instanceof AppEntity) {
            throw StoreException::extensionNotFoundFromTechnicalName($technicalName);
        }

        return $app;
    }

    public function getAppEntityFromId(string $id, Context $context): AppEntity
    {
        $criteria = new Criteria([$id]);
        $app = $this->appRepository->search($criteria, $context)->getEntities()->first();

        if (!$app instanceof AppEntity) {
            throw StoreException::extensionNotFoundFromId($id);
        }

        return $app;
    }

    protected function getDecorated(): AbstractExtensionDataProvider
    {
        throw new DecorationPatternException(self::class);
    }
}
