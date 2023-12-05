<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class InvalidPageQueryException extends SnapAdminHttpException
{
    public function __construct(mixed $page)
    {
        parent::__construct('The page parameter must be a positive integer. Given: {{ page }}', ['page' => $page]);
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_PAGE_QUERY';
    }
}
