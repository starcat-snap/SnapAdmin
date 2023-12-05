<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class InvalidFilterQueryException extends SnapAdminHttpException
{
    public function __construct(
        string                  $message,
        private readonly string $path = ''
    )
    {
        parent::__construct('{{ message }}', ['message' => $message]);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_FILTER_QUERY';
    }
}
