<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('content')]
class DocumentNumberAlreadyExistsException extends SnapAdminHttpException
{
    public function __construct(?string $number)
    {
        parent::__construct('Document number {{number}} has already been allocated.', [
            'number' => $number,
        ]);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'DOCUMENT__NUMBER_ALREADY_EXISTS';
    }
}
