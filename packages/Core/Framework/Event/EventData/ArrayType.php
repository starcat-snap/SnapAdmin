<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event\EventData;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('business-ops')]
class ArrayType implements EventDataType
{
    final public const TYPE = 'array';

    public function __construct(private readonly EventDataType $type)
    {
    }

    public function toArray(): array
    {
        return [
            'type' => self::TYPE,
            'of' => $this->type->toArray(),
        ];
    }
}
