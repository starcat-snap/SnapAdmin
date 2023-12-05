<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\FieldException;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class UnexpectedFieldException extends SnapAdminHttpException implements WriteFieldException
{
    private readonly string $fieldName;

    public function __construct(
        private readonly string $path,
        string                  $fieldName
    )
    {
        parent::__construct(
            'Unexpected field: {{ field }}',
            ['field' => $fieldName]
        );
        $this->fieldName = $fieldName;
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__WRITE_UNEXPECTED_FIELD_ERROR';
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
