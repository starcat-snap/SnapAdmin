<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomField;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<CustomFieldEntity>
 */
#[Package('core')]
class CustomFieldCollection extends EntityCollection
{
    public function filterByType(string $type): self
    {
        return $this->filter(fn (CustomFieldEntity $attribute) => $attribute->getType() === $type);
    }

    public function getApiAlias(): string
    {
        return 'custom_field_collection';
    }

    protected function getExpectedClass(): string
    {
        return CustomFieldEntity::class;
    }
}
