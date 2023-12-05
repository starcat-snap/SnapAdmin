<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Acl;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Api\ApiException;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Routing\KernelListenerPriorities;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\PlatformRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @internal
 */
#[Package('core')]
class AclAnnotationValidator implements EventSubscriberInterface
{
    /**
     * @internal
     */
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [
                ['validate', KernelListenerPriorities::KERNEL_CONTROLLER_EVENT_SCOPE_VALIDATE],
            ],
        ];
    }

    public function validate(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        $privileges = $request->attributes->get(PlatformRequest::ATTRIBUTE_ACL);

        if (!$privileges) {
            return;
        }

        $context = $request->attributes->get(PlatformRequest::ATTRIBUTE_CONTEXT_OBJECT);
        if (!$context instanceof Context) {
            throw ApiException::missingPrivileges([]);
        }

        foreach ($privileges as $privilege) {
            if ($privilege === 'app') {
                if ($context->isAllowed('app.all')) {
                    return;
                }

                $privilege = $this->getAppPrivilege($request);
            }

            if (!$context->isAllowed($privilege)) {
                throw ApiException::missingPrivileges([$privilege]);
            }
        }
    }

    private function getAppPrivilege(Request $request): string
    {
        $actionId = $request->get('id');

        if (empty($actionId)) {
            throw ApiException::appIdParameterIsMissing();
        }

        $appName = $this->connection->fetchOne(
            '
                SELECT `app`.`name` AS `name`
                FROM `app`
                INNER JOIN `app_action_button` ON `app`.`id` = `app_action_button`.`app_id`
                WHERE `app_action_button`.`id` = :id
            ',
            ['id' => Uuid::fromHexToBytes($actionId)],
        );

        return 'app.' . $appName;
    }
}
