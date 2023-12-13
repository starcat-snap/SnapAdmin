<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\FieldAccessorBuilder\CustomFieldsAccessorBuilder;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\CustomFieldsSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class CustomFields extends JsonField
{
    public function __construct(
        string $storageName = 'custom_fields',
        string $propertyName = 'customFields'
    ) {
        parent::__construct($storageName, $propertyName);
    }

    /**
     * @param list<Field> $propertyMapping
     */
    public function setPropertyMapping(array $propertyMapping): void
    {
        $this->propertyMapping = $propertyMapping;
    }

    protected function getSerializerClass(): string
    {
        return CustomFieldsSerializer::class;
    }

    protected function getAccessorBuilderClass(): ?string
    {
        return CustomFieldsAccessorBuilder::class;
    }
}
