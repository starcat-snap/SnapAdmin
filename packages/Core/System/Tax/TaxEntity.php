<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRule\TaxRuleCollection;

#[Package('checkout')]
class TaxEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @var float
     */
    protected $taxRate;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $position;


    /**
     * @var TaxRuleCollection|null
     */
    protected $rules;


    public function getTaxRate(): float
    {
        return $this->taxRate;
    }

    public function setTaxRate(float $taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }


    public function getRules(): ?TaxRuleCollection
    {
        return $this->rules;
    }

    public function setRules(TaxRuleCollection $rules): void
    {
        $this->rules = $rules;
    }
}
