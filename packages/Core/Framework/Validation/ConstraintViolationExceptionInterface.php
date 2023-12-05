<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Validation;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminException;
use Symfony\Component\Validator\ConstraintViolationList;

#[Package('core')]
interface ConstraintViolationExceptionInterface extends SnapAdminException
{
    public function getViolations(): ConstraintViolationList;
}
