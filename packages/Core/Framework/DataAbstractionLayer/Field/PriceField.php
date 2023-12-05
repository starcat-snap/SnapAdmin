<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\FieldAccessorBuilder\PriceFieldAccessorBuilder;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\PriceFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class PriceField extends JsonField
{
    public function __construct(
        string $storageName,
        string $propertyName
    ) {
        parent::__construct($storageName, $propertyName);
    }

    protected function getSerializerClass(): string
    {
        return PriceFieldSerializer::class;
    }

    protected function getAccessorBuilderClass(): ?string
    {
        return PriceFieldAccessorBuilder::class;
    }
}
