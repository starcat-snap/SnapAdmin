<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching\Aware;

use SnapAdmin\Core\Framework\Event\IsFlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
#[IsFlowEventAware]
interface OrderTransactionAware
{
    public const ORDER_TRANSACTION_ID = 'orderTransactionId';

    public const ORDER_TRANSACTION = 'orderTransaction';

    public function getOrderTransactionId(): string;
}
