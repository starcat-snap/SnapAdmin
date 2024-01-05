<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\TaxRuleType;

use SnapAdmin\Core\Checkout\Cart\Delivery\Struct\ShippingLocation;
use SnapAdmin\Core\Checkout\Customer\CustomerEntity;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRule\TaxRuleEntity;

#[Package('checkout')]
class IndividualStatesRuleTypeFilter extends AbstractTaxRuleTypeFilter
{
    final public const TECHNICAL_NAME = 'individual_states';

    public function match(TaxRuleEntity $taxRuleEntity, ?CustomerEntity $customer, ShippingLocation $shippingLocation): bool
    {
        if ($taxRuleEntity->getType()->getTechnicalName() !== self::TECHNICAL_NAME
            || !$this->metPreconditions($taxRuleEntity, $shippingLocation)
        ) {
            return false;
        }

        $stateId = $this->getStateId($shippingLocation);
        $states = $taxRuleEntity->getData()['states'];

        if (!\in_array($stateId, $states, true)) {
            return false;
        }

        if ($taxRuleEntity->getActiveFrom() !== null) {
            return $this->isTaxActive($taxRuleEntity);
        }

        return true;
    }

    private function metPreconditions(TaxRuleEntity $taxRuleEntity, ShippingLocation $shippingLocation): bool
    {
        if ($this->getStateId($shippingLocation) === null) {
            return false;
        }

        return $shippingLocation->getCountry()->getId() === $taxRuleEntity->getCountryId();
    }

    private function getStateId(ShippingLocation $shippingLocation): ?string
    {
        return $shippingLocation->getState() !== null ? $shippingLocation->getState()->getId() : null;
    }
}
