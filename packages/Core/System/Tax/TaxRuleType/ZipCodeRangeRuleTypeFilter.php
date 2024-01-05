<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\TaxRuleType;

use SnapAdmin\Core\Checkout\Cart\Delivery\Struct\ShippingLocation;
use SnapAdmin\Core\Checkout\Customer\CustomerEntity;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRule\TaxRuleEntity;

#[Package('checkout')]
class ZipCodeRangeRuleTypeFilter extends AbstractTaxRuleTypeFilter
{
    final public const TECHNICAL_NAME = 'zip_code_range';

    public function match(TaxRuleEntity $taxRuleEntity, ?CustomerEntity $customer, ShippingLocation $shippingLocation): bool
    {
        if ($taxRuleEntity->getType()->getTechnicalName() !== self::TECHNICAL_NAME
            || !$this->metPreconditions($taxRuleEntity, $shippingLocation)
        ) {
            return false;
        }

        $zipCode = $this->getZipCode($shippingLocation);

        $toZipCode = $taxRuleEntity->getData()['toZipCode'];
        $fromZipCode = $taxRuleEntity->getData()['fromZipCode'];

        if ($fromZipCode === null || $toZipCode === null || $zipCode < $fromZipCode || $zipCode > $toZipCode) {
            return false;
        }

        if ($taxRuleEntity->getActiveFrom() !== null) {
            return $this->isTaxActive($taxRuleEntity);
        }

        return true;
    }

    private function metPreconditions(TaxRuleEntity $taxRuleEntity, ShippingLocation $shippingLocation): bool
    {
        if ($this->getZipCode($shippingLocation) === null) {
            return false;
        }

        return $shippingLocation->getCountry()->getId() === $taxRuleEntity->getCountryId();
    }

    private function getZipCode(ShippingLocation $shippingLocation): ?string
    {
        return $shippingLocation->getAddress() !== null ? $shippingLocation->getAddress()->getZipcode() : null;
    }
}
