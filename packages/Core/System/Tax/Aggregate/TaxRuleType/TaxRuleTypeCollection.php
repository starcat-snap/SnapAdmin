<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\Aggregate\TaxRuleType;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<TaxRuleTypeEntity>
 */
#[Package('checkout')]
class TaxRuleTypeCollection extends EntityCollection
{
    public function getByTechnicalName(string $technicalName): ?TaxRuleTypeEntity
    {
        foreach ($this->getIterator() as $ruleTypeEntity) {
            if ($ruleTypeEntity->getTechnicalName() === $technicalName) {
                return $ruleTypeEntity;
            }
        }

        return null;
    }

    public function getApiAlias(): string
    {
        return 'tax_rule_type_collection';
    }

    protected function getExpectedClass(): string
    {
        return TaxRuleTypeEntity::class;
    }
}
