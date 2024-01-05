<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country\Aggregate\CountryStateCity;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;

class CountryStateCityCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'country_state_city_collection';
    }

    protected function getExpectedClass(): string
    {
        return CountryStateCityDistrictEntity::class;
    }
}
