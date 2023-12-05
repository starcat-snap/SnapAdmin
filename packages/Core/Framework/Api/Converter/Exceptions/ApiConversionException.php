<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Converter\Exceptions;

use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminException;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated tag:v6.6.0 - Will be removed as it is not used anymore
 */
#[Package('core')]
class ApiConversionException extends SnapAdminHttpException
{
    /**
     * @param array<string, \Throwable[]> $exceptions
     */
    public function __construct(private array $exceptions = [])
    {
        parent::__construct('Api Version conversion failed, got {{ numberOfFailures }} failure(s).', ['numberOfFailures' => \count($exceptions)]);
    }

    public function add(\Throwable $exception, string $pointer): void
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(__CLASS__, 'v6.6.0.0')
        );

        $this->exceptions[$pointer][] = $exception;
    }

    public function getStatusCode(): int
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(__CLASS__, 'v6.6.0.0')
        );

        return Response::HTTP_BAD_REQUEST;
    }

    public function tryToThrow(): void
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(__CLASS__, 'v6.6.0.0')
        );

        if (empty($this->exceptions)) {
            return;
        }

        throw $this;
    }

    public function getErrors(bool $withTrace = false): \Generator
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(__CLASS__, 'v6.6.0.0')
        );

        foreach ($this->exceptions as $pointer => $innerExceptions) {
            /** @var SnapAdminException $exception */
            foreach ($innerExceptions as $exception) {
                $parameters = [];
                $errorCode = 0;

                if ($exception instanceof SnapAdminException) {
                    $parameters = $exception->getParameters();
                    $errorCode = $exception->getErrorCode();
                }

                $error = [
                    'status' => (string)$this->getStatusCode(),
                    'code' => $errorCode,
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
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(__CLASS__, 'v6.6.0.0')
        );

        return 'FRAMEWORK__API_VERSION_CONVERSION';
    }
}
