<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\TaxRuleType;

use SnapAdmin\Core\Checkout\Cart\Delivery\Struct\ShippingLocation;
use SnapAdmin\Core\Checkout\Customer\CustomerEntity;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRule\TaxRuleEntity;

#[Package('checkout')]
interface TaxRuleTypeFilterInterface
{
    public function match(TaxRuleEntity $taxRuleEntity, ?CustomerEntity $customer, ShippingLocation $shippingLocation): bool;
}
