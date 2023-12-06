<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Response;

use SnapAdmin\Core\Framework\Api\Context\ContextSource;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
interface ResponseFactoryInterface
{
    public function supports(string $contentType, ContextSource $origin): bool;

    public function createDetailResponse(
        Criteria $criteria,
        Entity $entity,
        EntityDefinition $definition,
        Request $request,
        Context $context,
        bool $setLocationHeader = false
    ): Response;

    /**
     * @template TEntityCollection of EntityCollection
     *
     * @param EntitySearchResult<TEntityCollection> $searchResult
     */
    public function createListingResponse(
        Criteria $criteria,
        EntitySearchResult $searchResult,
        EntityDefinition $definition,
        Request $request,
        Context $context
    ): Response;

    public function createRedirectResponse(
        EntityDefinition $definition,
        string $id,
        Request $request,
        Context $context
    ): Response;
}
