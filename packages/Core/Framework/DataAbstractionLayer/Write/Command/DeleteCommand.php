<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command;

use SnapAdmin\Core\Framework\Api\Acl\Role\AclRoleDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @phpstan-ignore-next-line cannot be final, as it is extended, also designed to be used directly
 */
#[Package('core')]
class DeleteCommand extends WriteCommand implements ChangeSetAware
{
    use ChangeSetAwareTrait;

    /**
     * @param array<string> $primaryKey
     */
    public function __construct(
        EntityDefinition $definition,
        array $primaryKey,
        EntityExistence $existence
    ) {
        parent::__construct($definition, [], $primaryKey, $existence, '');
    }

    public function isValid(): bool
    {
        return (bool) \count($this->primaryKey);
    }

    public function getPrivilege(): ?string
    {
        return AclRoleDefinition::PRIVILEGE_DELETE;
    }
}
