<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminException;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class SearchRequestException extends SnapAdminHttpException
{
    public function __construct(private array $exceptions = [])
    {
        parent::__construct('Mapping failed, got {{ numberOfFailures }} failure(s).', ['numberOfFailures' => \count($exceptions)]);
    }

    public function add(\Throwable $exception, string $pointer): void
    {
        $this->exceptions[$pointer][] = $exception;
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function tryToThrow(): void
    {
        if (empty($this->exceptions)) {
            return;
        }

        throw $this;
    }

    public function getErrors(bool $withTrace = false): \Generator
    {
        foreach ($this->exceptions as $pointer => $innerExceptions) {
            /** @var SnapAdminException $exception */
            foreach ($innerExceptions as $exception) {
                $parameters = [];
                if ($exception instanceof SnapAdminException) {
                    $parameters = $exception->getParameters();
                }

                $error = [
                    'status' => (string)$this->getStatusCode(),
                    'code' => $exception->getErrorCode(),
                    'title' => Response::$statusTexts[Response::HTTP_BAD_REQUEST],
                    'detail' => $exception->getMessage(),
                    'source' => ['pointer' => $pointer],
                    'meta' => [
                        'parameters' => $parameters,
                    ],
                ];

                if ($withTrace) {
                    $error['trace'] = $exception->getTraceAsString();
                }

                yield $error;
            }
        }
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__SEARCH_REQUEST_MAPPING';
    }
}
