<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomField\Aggregate\CustomFieldSet;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<CustomFieldSetEntity>
 */
#[Package('system-settings')]
class CustomFieldSetCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'custom_field_set_collection';
    }

    protected function getExpectedClass(): string
    {
        return CustomFieldSetEntity::class;
    }
}
