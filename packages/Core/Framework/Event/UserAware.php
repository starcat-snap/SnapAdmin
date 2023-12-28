<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('system-settings')]
#[IsFlowEventAware]
interface UserAware
{
    public const USER_RECOVERY = 'userRecovery';

    public const USER_RECOVERY_ID = 'userRecoveryId';

    public function getUserId(): string;
}
