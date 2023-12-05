<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<CustomEntityEntity>
 */
#[Package('core')]
class CustomEntityCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'custom_entity_collection';
    }

    protected function getExpectedClass(): string
    {
        return CustomEntityEntity::class;
    }
}
