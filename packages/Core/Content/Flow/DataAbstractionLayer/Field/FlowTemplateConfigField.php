<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\DataAbstractionLayer\Field;

use SnapAdmin\Core\Content\Flow\DataAbstractionLayer\FieldSerializer\FlowTemplateConfigFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\JsonField;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('services-settings')]
class FlowTemplateConfigField extends JsonField
{
    public function __construct(
        string $storageName,
        string $propertyName
    ) {
        $this->storageName = $storageName;
        parent::__construct($storageName, $propertyName);
    }

    protected function getSerializerClass(): string
    {
        return FlowTemplateConfigFieldSerializer::class;
    }
}
