<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @deprecated tag:v6.6.0 - reason:class-hierarchy-change - extends of FlowEventAware will be removed, implement the interface inside your event
 */
#[Package('business-ops')]
interface CustomerAware extends FlowEventAware
{
    public const CUSTOMER_ID = 'customerId';

    public const CUSTOMER = 'customer';

    public function getCustomerId(): string;
}
