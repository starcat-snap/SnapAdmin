<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class CustomEntityNotFoundException extends SnapAdminHttpException
{
    public function __construct(string $customEntity)
    {
        parent::__construct(
            'Custom Entity "{{ entityName }}" does not exist.',
            ['entityName' => $customEntity]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__CUSTOM_ENTITY_NOT_FOUND';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
