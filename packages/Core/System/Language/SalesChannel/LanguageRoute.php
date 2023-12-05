<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Language\SalesChannel;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Exception\DecorationPatternException;
use SnapAdmin\Core\System\Language\LanguageCollection;
use SnapAdmin\Core\System\SalesChannel\Entity\SalesChannelRepository;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['store-api']])]
#[Package('buyers-experience')]
class LanguageRoute extends AbstractLanguageRoute
{
    /**
     * @internal
     *
     * @param SalesChannelRepository<LanguageCollection> $repository
     */
    public function __construct(private readonly SalesChannelRepository $repository)
    {
    }

    public function getDecorated(): AbstractLanguageRoute
    {
        throw new DecorationPatternException(self::class);
    }

    #[Route(path: '/store-api/language', name: 'store-api.language', methods: ['GET', 'POST'], defaults: ['_entity' => 'language'])]
    public function load(Request $request, SalesChannelContext $context, Criteria $criteria): LanguageRouteResponse
    {
        $criteria->addAssociation('translationCode');

        return new LanguageRouteResponse(
            $this->repository->search($criteria, $context)
        );
    }
}
