<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
#[Package('services-settings')]
class CustomTriggerByNameNotFoundException extends SnapAdminHttpException
{
    public function __construct(string $eventName)
    {
        parent::__construct(
            'The provided event name {{ eventName }} is invalid or uninstalled and no custom trigger could be found.',
            ['eventName' => $eventName]
        );
    }

    public function getErrorCode(): string
    {
        return 'ADMINISTRATION__CUSTOM_TRIGGER_BY_NAME_NOT_FOUND';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
