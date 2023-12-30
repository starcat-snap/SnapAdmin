<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('content')]
class InvalidDocumentRendererException extends SnapAdminHttpException
{
    public function __construct(string $type)
    {
        $message = sprintf('Unable to find a document renderer with type "%s"', $type);
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'DOCUMENT__INVALID_RENDERER_TYPE';
    }
}
