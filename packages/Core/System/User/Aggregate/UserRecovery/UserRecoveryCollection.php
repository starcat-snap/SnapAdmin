<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\User\Aggregate\UserRecovery;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<UserRecoveryEntity>
 */
#[Package('system-settings')]
class UserRecoveryCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'user_recovery_collection';
    }

    protected function getExpectedClass(): string
    {
        return UserRecoveryEntity::class;
    }
}
