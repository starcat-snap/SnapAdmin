<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Pricing;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<Price>
 */
#[Package('core')]
class PriceCollection extends Collection
{
    public function getApiAlias(): string
    {
        return 'price_collection';
    }

    protected function getExpectedClass(): ?string
    {
        return Price::class;
    }
}
