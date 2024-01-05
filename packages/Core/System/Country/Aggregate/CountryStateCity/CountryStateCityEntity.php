<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country\Aggregate\CountryStateCity;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class CountryStateCityEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;
}
