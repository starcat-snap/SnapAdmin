<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\Aggregate\TaxRule;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Country\CountryEntity;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRuleType\TaxRuleTypeEntity;
use SnapAdmin\Core\System\Tax\TaxEntity;

#[Package('checkout')]
class TaxRuleEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $taxId;

    /**
     * @var TaxEntity|null
     */
    protected $tax;

    /**
     * @var string
     */
    protected $countryId;

    /**
     * @var CountryEntity|null
     */
    protected $country;

    /**
     * @var string
     */
    protected $taxRuleTypeId;

    /**
     * @var TaxRuleTypeEntity
     */
    protected $type;

    /**
     * @var float
     */
    protected $taxRate;

    /**
     * @var array|null
     */
    protected $data;

    /**
     * @var \DateTimeInterface|null
     */
    protected $activeFrom;

    public function getTaxId(): string
    {
        return $this->taxId;
    }

    public function setTaxId(string $taxId): void
    {
        $this->taxId = $taxId;
    }

    public function getTax(): ?TaxEntity
    {
        return $this->tax;
    }

    public function setTax(?TaxEntity $tax): void
    {
        $this->tax = $tax;
    }

    public function getCountryId(): string
    {
        return $this->countryId;
    }

    public function setCountryId(string $countryId): void
    {
        $this->countryId = $countryId;
    }

    public function getCountry(): ?CountryEntity
    {
        return $this->country;
    }

    public function setCountry(?CountryEntity $country): void
    {
        $this->country = $country;
    }

    public function getTaxRuleTypeId(): string
    {
        return $this->taxRuleTypeId;
    }

    public function setTaxRuleTypeId(string $taxRuleTypeId): void
    {
        $this->taxRuleTypeId = $taxRuleTypeId;
    }

    public function getType(): TaxRuleTypeEntity
    {
        return $this->type;
    }

    public function setType(TaxRuleTypeEntity $type): void
    {
        $this->type = $type;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTaxRate(): float
    {
        return $this->taxRate;
    }

    public function setTaxRate(float $taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    public function getActiveFrom(): ?\DateTimeInterface
    {
        return $this->activeFrom;
    }

    public function setActiveFrom(?\DateTimeInterface $activeFrom): void
    {
        $this->activeFrom = $activeFrom;
    }
}
