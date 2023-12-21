<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Controller;

use SnapAdmin\Administration\Notification\Exception\NotificationThrottledException;
use SnapAdmin\Administration\Notification\NotificationService;
use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\Api\Context\Exception\InvalidContextSourceException;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\RateLimiter\Exception\RateLimitExceededException;
use SnapAdmin\Core\Framework\RateLimiter\RateLimiter;
use SnapAdmin\Core\Framework\Routing\RoutingException;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
#[Package('administration')]
class NotificationController extends AbstractController
{
    final public const NOTIFICATION = 'notification';

    final public const LIMIT = 5;

    /**
     * @internal
     */
    public function __construct(
        private readonly RateLimiter $rateLimiter,
        private readonly NotificationService $notificationService
    ) {
    }

    #[Route(path: '/api/notification', name: 'api.notification', defaults: ['_acl' => ['notification:create']], methods: ['POST'])]
    public function saveNotification(Request $request, Context $context): Response
    {
        $status = $request->request->get('status');
        $message = $request->request->get('message');
        $adminOnly = (bool) $request->request->get('adminOnly', false);
        $requiredPrivileges = $request->request->all('requiredPrivileges');

        $source = $context->getSource();
        if (!$source instanceof AdminApiSource) {
            throw new InvalidContextSourceException(AdminApiSource::class, $context->getSource()::class);
        }

        if (empty($status)) {
            throw RoutingException::missingRequestParameter('status');
        }

        if (empty($message)) {
            throw RoutingException::missingRequestParameter('message');
        }

        if ($requiredPrivileges === []) {
            throw RoutingException::invalidRequestParameter('requiredPrivileges');
        }

        $createdByUserId = $source->getUserId();

        try {
            if ($createdByUserId) {
                $this->rateLimiter->ensureAccepted(self::NOTIFICATION, $createdByUserId);
            }
        } catch (RateLimitExceededException $exception) {
            throw new NotificationThrottledException($exception->getWaitTime(), $exception);
        }

        $notificationId = Uuid::randomHex();
        $this->notificationService->createNotification([
            'id' => $notificationId,
            'status' => $status,
            'message' => $message,
            'adminOnly' => $adminOnly,
            'requiredPrivileges' => $requiredPrivileges,
            'createdByUserId' => $createdByUserId,
        ], $context);

        return new JsonResponse(['id' => $notificationId]);
    }

    #[Route(path: '/api/notification/message', name: 'api.notification.message', methods: ['GET'])]
    public function fetchNotification(Request $request, Context $context): Response
    {
        $limit = $request->query->get('limit');
        $limit = $limit ? (int) $limit : self::LIMIT;
        $latestTimestamp = $request->query->has('latestTimestamp') ? (string) $request->query->get('latestTimestamp') : null;

        $responseData = $this->notificationService->getNotifications($context, $limit, $latestTimestamp);

        return new JsonResponse($responseData);
    }
}
