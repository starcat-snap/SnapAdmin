<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\FieldException;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminException;

#[Package('core')]
interface WriteFieldException extends SnapAdminException
{
    public function getPath(): string;
}
