<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Language\Exception;

use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;


class LanguageForeignKeyDeleteException extends SnapAdminHttpException
{
    public function __construct(?\Throwable $e = null)
    {
        parent::__construct(
            'The language cannot be deleted because foreign key constraints exist.',
            [],
            $e
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__LANGUAGE_FOREIGN_KEY_DELETE';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
