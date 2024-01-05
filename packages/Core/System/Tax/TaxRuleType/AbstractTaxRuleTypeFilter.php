<?php

declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\TaxRuleType;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRule\TaxRuleEntity;

#[Package('checkout')]
abstract class AbstractTaxRuleTypeFilter implements TaxRuleTypeFilterInterface
{
    protected function isTaxActive(TaxRuleEntity $taxRuleEntity): bool
    {
        return $taxRuleEntity->getActiveFrom() < (new \DateTime())->setTimezone(new \DateTimeZone('UTC'));
    }
}
