<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Acl\Role;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<AclRoleEntity>
 */
#[Package('core')]
class AclRoleCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'dal_acl_role_collection';
    }

    protected function getExpectedClass(): string
    {
        return AclRoleEntity::class;
    }
}
