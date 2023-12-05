<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\RateLimiter\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class RateLimitExceededException extends SnapAdminHttpException
{
    private readonly int $now;

    public function __construct(
        private readonly int $retryAfter,
        ?\Throwable          $e = null
    )
    {
        $this->now = time();

        parent::__construct(
            'Too many requests, try again in {{ seconds }} seconds.',
            ['seconds' => $this->getWaitTime()],
            $e
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__RATE_LIMIT_EXCEEDED';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_TOO_MANY_REQUESTS;
    }

    public function getWaitTime(): int
    {
        return $this->retryAfter - $this->now;
    }
}
