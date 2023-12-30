<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('content')]
class InvalidDocumentException extends SnapAdminHttpException
{
    public function __construct(string $documentId)
    {
        $message = sprintf('The document with id "%s" is invalid or could not be found.', $documentId);
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'DOCUMENT__INVALID_DOCUMENT_ID';
    }
}
