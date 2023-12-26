<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Rule\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('services-settings')]
class UnsupportedOperatorException extends SnapAdminHttpException
{
    /**
     * @var string
     */
    protected $operator;

    /**
     * @var string
     */
    protected $class;

    public function __construct(
        string $operator,
        string $class
    ) {
        $this->operator = $operator;
        $this->class = $class;

        parent::__construct(
            'Unsupported operator {{ operator }} in {{ class }}',
            ['operator' => $operator, 'class' => $class]
        );
    }

    public function getOperator(): string
    {
        return $this->operator;
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
        return 'CONTENT__RULE_OPERATOR_NOT_SUPPORTED';
    }
}
