<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country\Aggregate\CountryStateCityDistrict;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class CountryStateCityDistrictEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;
}
