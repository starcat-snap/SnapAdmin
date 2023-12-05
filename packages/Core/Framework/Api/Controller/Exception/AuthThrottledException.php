<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Controller\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class AuthThrottledException extends SnapAdminHttpException
{
    public function __construct(
        private readonly int $waitTime,
        ?\Throwable $e = null
    ) {
        parent::__construct(
            'Auth throttled for {{ seconds }} seconds.',
            ['seconds' => $this->getWaitTime()],
            $e
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__AUTH_THROTTLED';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_TOO_MANY_REQUESTS;
    }

    public function getWaitTime(): int
    {
        return $this->waitTime;
    }
}
