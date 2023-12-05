<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Validation\Constraint;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\Framework\Validation\Constraint\Uuid as UuidConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[Package('core')]
class UuidValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UuidConstraint) {
            throw new UnexpectedTypeException($constraint, UuidConstraint::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if ($value === null || $value === '') {
            return;
        }

        if (!\is_string($value)) {
            $this->context->buildViolation(UuidConstraint::INVALID_TYPE_MESSAGE)
                ->addViolation();

            return;
        }

        if (!Uuid::isValid($value)) {
            $this->context->buildViolation(UuidConstraint::INVALID_MESSAGE)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
