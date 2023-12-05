<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class ExpectationFailedException extends SnapAdminHttpException
{
    /**
     * @param array<string> $fails
     */
    public function __construct(private readonly array $fails)
    {
        parent::__construct('API Expectations failed', []);
    }

    /**
     * @return array<string> $failedExpectations
     */
    public function getParameters(): array
    {
        return $this->fails;
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__API_EXPECTATION_FAILED';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_EXPECTATION_FAILED;
    }
}
