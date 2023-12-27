<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Pricing;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

#[Package('core')]
class Price extends Struct
{

    /**
     * @var float
     */
    protected $price;

    /**
     * @var bool
     */
    protected $linked;

    /**
     * @var Price|null
     */
    protected $listPrice;

    /**
     * @var array|null
     */
    protected $percentage;

    /**
     * @var Price|null
     */
    protected $regulationPrice;

    public function __construct(
        float $price,
        bool $linked,
        ?Price $listPrice = null,
        ?array $percentage = null,
        ?Price $regulationPrice = null
    ) {
        $this->price = $price;
        $this->linked = $linked;
        $this->listPrice = $listPrice;
        $this->percentage = $percentage;
        $this->regulationPrice = $regulationPrice;
    }


    public function getLinked(): bool
    {
        return $this->linked;
    }

    public function setLinked(bool $linked): void
    {
        $this->linked = $linked;
    }

    public function add(self $price): void
    {
        $this->price += $price->getPrice();
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setListPrice(?Price $listPrice): void
    {
        $this->listPrice = $listPrice;
    }

    public function getListPrice(): ?Price
    {
        return $this->listPrice;
    }

    public function getPercentage(): ?array
    {
        return $this->percentage;
    }

    public function setPercentage(?array $percentage): void
    {
        $this->percentage = $percentage;
    }

    public function getApiAlias(): string
    {
        return 'price';
    }

    public function getRegulationPrice(): ?Price
    {
        return $this->regulationPrice;
    }

    public function setRegulationPrice(?Price $regulationPrice): void
    {
        $this->regulationPrice = $regulationPrice;
    }
}
