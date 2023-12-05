<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command;

use SnapAdmin\Core\Framework\Api\Acl\Role\AclRoleDefinition;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class InsertCommand extends WriteCommand
{
    public function getPrivilege(): ?string
    {
        return AclRoleDefinition::PRIVILEGE_CREATE;
    }
}
