<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country\Aggregate\CountryStateCityTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<CountryStateCityTranslationEntity>
 */
#[Package('system')]
class CountryStateCityTranslationCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'country_state_city_translation_collection';
    }

    protected function getExpectedClass(): string
    {
        return CountryStateCityTranslationEntity::class;
    }
}
