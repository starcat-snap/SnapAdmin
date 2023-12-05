<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\FieldAccessorBuilder\JsonFieldAccessorBuilder;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\CashRoundingConfigFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class CashRoundingConfigField extends JsonField
{
    public function __construct(
        string $storageName,
        string $propertyName
    )
    {
        parent::__construct($storageName, $propertyName, [
            new IntField('decimals', 'decimals', 0),
            new FloatField('interval', 'interval'),
            new BoolField('roundForNet', 'roundForNet'),
        ]);
    }

    protected function getSerializerClass(): string
    {
        return CashRoundingConfigFieldSerializer::class;
    }

    protected function getAccessorBuilderClass(): ?string
    {
        return JsonFieldAccessorBuilder::class;
    }
}
