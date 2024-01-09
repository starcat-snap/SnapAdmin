<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Services;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Exception\DecorationPatternException;
use SnapAdmin\Core\Framework\Plugin\PluginCollection;
use SnapAdmin\Core\Framework\Store\Search\ExtensionCriteria;
use SnapAdmin\Core\Framework\Store\Struct\ExtensionCollection;
use SnapAdmin\Core\Framework\Store\Struct\ExtensionStruct;
use SnapAdmin\Core\Framework\Store\Struct\ReviewCollection;
use SnapAdmin\Core\Framework\Store\Struct\ReviewSummaryStruct;

/**
 * @internal
 */

/**
 * @phpstan-import-type RequestQueryParameters from StoreClient
 * @phpstan-import-type ExtensionListingFilter from StoreClient
 * @phpstan-import-type ExtensionListingSorting from StoreClient
 */
#[Package('services-settings')]
class ExtensionDataProvider extends AbstractExtensionDataProvider
{
    final public const HEADER_NAME_TOTAL_COUNT = 'SA-Meta-Total';

    public function __construct(
        private readonly ExtensionLoader        $extensionLoader,
        private readonly EntityRepository       $pluginRepository,
        private readonly ExtensionListingLoader $extensionListingLoader,
        private readonly StoreClient            $client
    )
    {
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

    /**
     * @param RequestQueryParameters $parameters
     *
     * @return array{filter: list<ExtensionListingFilter>, sorting: ExtensionListingSorting}
     */
    public function getListingFilters(array $parameters, Context $context): array
    {
        return $this->client->listListingFilters($parameters, $context);
    }

    public function getExtensionDetails(int $id, Context $context): ExtensionStruct
    {
        $detailResponse = $this->client->extensionDetail($id, $context);

        return $this->extensionLoader->loadFromArray($context, $detailResponse);
    }

    /**
     * @return array{summary: ReviewSummaryStruct, reviews: ReviewCollection}
     */
    public function getReviews(int $extensionId, ExtensionCriteria $criteria, Context $context): array
    {
        $reviewsResponse = $this->client->extensionDetailReviews($extensionId, $criteria, $context);

        return [
            'summary' => ReviewSummaryStruct::fromArray($reviewsResponse['summary']),
            'reviews' => new ReviewCollection($reviewsResponse['reviews']),
        ];
    }

    public function getListing(ExtensionCriteria $criteria, Context $context): ExtensionCollection
    {
        $listingResponse = $this->client->listExtensions($criteria, $context);
        $extensionListing = $this->extensionLoader->loadFromListingArray($context, $listingResponse['data']);

        $total = $listingResponse['headers'][self::HEADER_NAME_TOTAL_COUNT][0] ?? 0;
        $extensionListing->setTotal((int)$total);

        return $extensionListing;
    }

    protected function getDecorated(): AbstractExtensionDataProvider
    {
        throw new DecorationPatternException(self::class);
    }
}
