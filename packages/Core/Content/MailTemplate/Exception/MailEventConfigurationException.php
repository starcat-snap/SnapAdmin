<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('services-settings')]
class MailEventConfigurationException extends SnapAdminHttpException
{
    public function __construct(
        string $message,
        string $eventClass
    ) {
        parent::__construct(
            'Failed processing the mail event: {{ errorMessage }}. {{ eventClass }}',
            [
                'errorMessage' => $message,
                'eventClass' => $eventClass,
            ]
        );
    }

    public function getErrorCode(): string
    {
        return 'CONTENT__MAIL_INVALID_EVENT_CONFIGURATION';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
