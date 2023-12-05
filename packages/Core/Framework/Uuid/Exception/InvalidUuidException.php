<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Uuid\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class InvalidUuidException extends SnapAdminHttpException
{
    public function __construct(string $uuid)
    {
        parent::__construct('Value is not a valid UUID: {{ input }}', ['input' => $uuid]);
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_UUID';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
