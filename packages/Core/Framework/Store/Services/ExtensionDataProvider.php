<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Services;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Exception\DecorationPatternException;
use SnapAdmin\Core\Framework\Plugin\PluginCollection;
use SnapAdmin\Core\Framework\Store\Struct\ExtensionCollection;

/**
 * @internal
 */
#[Package('services-settings')]
class ExtensionDataProvider extends AbstractExtensionDataProvider
{
    final public const HEADER_NAME_TOTAL_COUNT = 'SA-Meta-Total';

    public function __construct(
        private readonly ExtensionLoader $extensionLoader,
        private readonly EntityRepository $pluginRepository,
        private readonly ExtensionListingLoader $extensionListingLoader
    ) {
    }

    public function getInstalledExtensions(Context $context, bool $loadCloudExtensions = true, ?Criteria $searchCriteria = null): ExtensionCollection
    {
        $criteria = $searchCriteria ?: new Criteria();
        $criteria->addAssociation('translations');

        /** @var PluginCollection $installedPlugins */
        $installedPlugins = $this->pluginRepository->search($criteria, $context)->getEntities();
        $pluginCollection = $this->extensionLoader->loadFromPluginCollection($context, $installedPlugins);

        $localExtensions = $pluginCollection;

        if ($loadCloudExtensions) {
            return $this->extensionListingLoader->load($localExtensions, $context);
        }

        return $localExtensions;
    }

    protected function getDecorated(): AbstractExtensionDataProvider
    {
        throw new DecorationPatternException(self::class);
    }
}
