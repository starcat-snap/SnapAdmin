<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class CascadeDeleteCommand extends DeleteCommand
{
    public function isValid(): bool
    {
        // prevent execution
        return false;
    }

    public function getPrivilege(): ?string
    {
        return null;
    }
}
