<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Controller;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\EntityIndexerRegistry;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\PlatformRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
#[Package('system-settings')]
class IndexingController extends AbstractController
{
    /**
     * @internal
     */
    public function __construct(
        private readonly EntityIndexerRegistry $registry
    ) {
    }

    #[Route(path: '/api/_action/indexing', name: 'api.action.indexing', methods: ['POST'])]
    public function indexing(Request $request): JsonResponse
    {
        $indexingSkips = array_filter(explode(',', (string) $request->headers->get(PlatformRequest::HEADER_INDEXING_SKIP, '')));

        $this->registry->sendIndexingMessage([], $indexingSkips);

        return new JsonResponse();
    }

    #[Route(path: '/api/_action/indexing/{indexer}', name: 'api.action.indexing.iterate', methods: ['POST'])]
    public function iterate(string $indexer, Request $request): JsonResponse
    {
        $indexingSkips = array_filter(explode(',', (string) $request->headers->get(PlatformRequest::HEADER_INDEXING_SKIP, '')));

        if (!$request->request->has('offset')) {
            throw new BadRequestHttpException('Parameter `offset` missing');
        }

        $indexer = $this->registry->getIndexer($indexer);

        $offset = ['offset' => $request->get('offset')];
        $message = $indexer ? $indexer->iterate($offset) : null;

        if ($message === null) {
            return new JsonResponse(['finish' => true]);
        }

        $message->addSkip(...$indexingSkips);

        if ($indexer) {
            $indexer->handle($message);
        }

        return new JsonResponse(['finish' => false, 'offset' => $message->getOffset()]);
    }
}
