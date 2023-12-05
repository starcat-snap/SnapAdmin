<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\ApiDefinition;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class ApiTypeNotFoundException extends SnapAdminHttpException
{
    public function __construct(string $type)
    {
        parent::__construct(
            'A api type "{{ type }}" was not found.',
            ['type' => $type]
        );
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__API_DEFINITION_TYPE_NOT_SUPPORTED';
    }
}
