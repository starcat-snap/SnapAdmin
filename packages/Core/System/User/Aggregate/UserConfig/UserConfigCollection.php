<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\User\Aggregate\UserConfig;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<UserConfigEntity>
 */
#[Package('system-settings')]
class UserConfigCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'user_config_collection';
    }

    protected function getExpectedClass(): string
    {
        return UserConfigEntity::class;
    }
}
