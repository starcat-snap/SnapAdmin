<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class ParentAssociationCanNotBeFetched extends SnapAdminHttpException
{
    public function __construct()
    {
        parent::__construct(
            'It is not possible to read the parent association directly. Please read the parents via a separate call over the repository'
        );
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__PARENT_ASSOCIATION_CAN_NOT_BE_FETCHED';
    }
}
