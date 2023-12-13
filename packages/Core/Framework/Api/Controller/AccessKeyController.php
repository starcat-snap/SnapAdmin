<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Controller;

use SnapAdmin\Core\Framework\Api\Util\AccessKeyHelper;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
#[Package('system-settings')]
class AccessKeyController extends AbstractController
{
    #[Route(path: '/api/_action/access-key/user', name: 'api.action.access-key.user', methods: ['GET'])]
    public function generateUserKey(): JsonResponse
    {
        return new JsonResponse([
            'accessKey' => AccessKeyHelper::generateAccessKey('user'),
            'secretAccessKey' => AccessKeyHelper::generateSecretAccessKey(),
        ]);
    }
}
