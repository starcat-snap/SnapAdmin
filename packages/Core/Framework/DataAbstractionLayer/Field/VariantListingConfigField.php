<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\VariantListingConfigFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class VariantListingConfigField extends JsonField
{
    public function __construct(
        string $storageName,
        string $propertyName
    ) {
        parent::__construct($storageName, $propertyName);
    }

    protected function getSerializerClass(): string
    {
        return VariantListingConfigFieldSerializer::class;
    }
}
