<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class ReadProtectedException extends SnapAdminHttpException
{
    public function __construct(
        string $field,
        string $scope
    ) {
        parent::__construct(
            'The field/association "{{ field }}" is read protected for your scope "{{ scope }}"',
            [
                'field' => $field,
                'scope' => $scope,
            ]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__READ_PROTECTED';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}
