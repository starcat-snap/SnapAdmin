<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\TimeZoneFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class TimeZoneField extends StringField
{
    protected function getSerializerClass(): string
    {
        return TimeZoneFieldSerializer::class;
    }
}
