<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomField\Aggregate\CustomFieldSetRelation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<CustomFieldSetRelationEntity>
 */
#[Package('system-settings')]
class CustomFieldSetRelationCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'custom_field_set_relation_collection';
    }

    protected function getExpectedClass(): string
    {
        return CustomFieldSetRelationEntity::class;
    }
}
