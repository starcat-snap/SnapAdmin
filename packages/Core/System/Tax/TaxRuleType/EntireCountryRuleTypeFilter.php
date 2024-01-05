<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\TaxRuleType;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRule\TaxRuleEntity;

#[Package('checkout')]
class EntireCountryRuleTypeFilter extends AbstractTaxRuleTypeFilter
{
    final public const TECHNICAL_NAME = 'entire_country';

    public function match(TaxRuleEntity $taxRuleEntity): bool
    {
        if ($taxRuleEntity->getType()->getTechnicalName() !== self::TECHNICAL_NAME
        ) {
            return false;
        }

        if ($taxRuleEntity->getActiveFrom() !== null) {
            return $this->isTaxActive($taxRuleEntity);
        }

        return true;
    }
}
