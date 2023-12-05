<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class InvalidSortingDirectionException extends SnapAdminHttpException
{
    public function __construct(string $direction)
    {
        parent::__construct(
            'The given sort direction "{{ direction }}" is invalid.',
            ['direction' => $direction]
        );
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_SORT_DIRECTION';
    }
}
