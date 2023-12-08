<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Snippet\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('system-settings')]
class InvalidSnippetFileException extends SnapAdminHttpException
{
    public function __construct(string $locale)
    {
        parent::__construct(
            'The base snippet file for locale {{ locale }} is not registered.',
            ['locale' => $locale]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_SNIPPET_FILE';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
