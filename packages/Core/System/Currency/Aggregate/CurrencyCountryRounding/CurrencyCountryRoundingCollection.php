<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Currency\Aggregate\CurrencyCountryRounding;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<CurrencyCountryRoundingEntity>
 */
#[Package('system')]
class CurrencyCountryRoundingCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'currency_country_rounding_collection';
    }

    protected function getExpectedClass(): string
    {
        return CurrencyCountryRoundingEntity::class;
    }
}
