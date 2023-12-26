<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching\Aware;

use SnapAdmin\Core\Framework\Event\IsFlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
#[IsFlowEventAware]
interface CustomerRecoveryAware
{
    public const CUSTOMER_RECOVERY_ID = 'customerRecoveryId';

    public const CUSTOMER_RECOVERY = 'customerRecovery';

    public function getCustomerRecoveryId(): string;
}
