<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Pricing;

use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<Price>
 */
#[Package('core')]
class PriceCollection extends Collection
{
    public function add($element): void
    {
        $this->set($element->getCurrencyId(), $element);
    }

    public function set($key, $element): void
    {
        parent::set($element->getCurrencyId(), $element);
    }

    public function getCurrencyPrice(string $currencyId, bool $fallback = true): ?Price
    {
        $price = $this->get($currencyId);

        if ($price) {
            return $price;
        }

        if ($currencyId === Defaults::CURRENCY) {
            return null;
        }

        if (!$fallback) {
            return null;
        }

        return $this->get(Defaults::CURRENCY);
    }

    public function getApiAlias(): string
    {
        return 'price_collection';
    }

    protected function getExpectedClass(): ?string
    {
        return Price::class;
    }
}
