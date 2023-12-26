<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Rule\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('services-settings')]
class UnsupportedValueException extends SnapAdminHttpException
{
    public function __construct(
        protected string $type,
        protected string $class
    ) {
        parent::__construct(
            'Unsupported value of type {{ type }} in {{ class }}',
            ['type' => $type, 'class' => $class]
        );
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'CONTENT__RULE_VALUE_NOT_SUPPORTED';
    }
}
