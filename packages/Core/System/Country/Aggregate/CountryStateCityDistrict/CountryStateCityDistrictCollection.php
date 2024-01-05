<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country\Aggregate\CountryStateCityDistrict;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;

class CountryStateCityDistrictCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'country_state_city_district_collection';
    }

    protected function getExpectedClass(): string
    {
        return CountryStateCityDistrictEntity::class;
    }
}
