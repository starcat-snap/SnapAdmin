<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('content')]
class DocumentGenerationException extends SnapAdminHttpException
{
    public function __construct(string $message = '')
    {
        $message = 'Unable to generate document. ' . $message;
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'DOCUMENT__GENERATION_ERROR';
    }
}
