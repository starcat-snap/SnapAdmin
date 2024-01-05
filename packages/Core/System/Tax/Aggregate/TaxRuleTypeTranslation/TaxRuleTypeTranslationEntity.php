<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\Aggregate\TaxRuleTypeTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\TranslationEntity;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRuleType\TaxRuleTypeEntity;

#[Package('checkout')]
class TaxRuleTypeTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $taxRuleTypeId;

    /**
     * @var string|null
     */
    protected $typeName;

    /**
     * @var TaxRuleTypeEntity|null
     */
    protected $taxRuleType;

    public function getTaxRuleTypeId(): string
    {
        return $this->taxRuleTypeId;
    }

    public function setTaxRuleTypeId(string $taxRuleTypeId): void
    {
        $this->taxRuleTypeId = $taxRuleTypeId;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(?string $typeName): void
    {
        $this->typeName = $typeName;
    }

    public function getTaxRuleType(): ?TaxRuleTypeEntity
    {
        return $this->taxRuleType;
    }

    public function setTaxRuleType(?TaxRuleTypeEntity $taxRuleType): void
    {
        $this->taxRuleType = $taxRuleType;
    }
}
