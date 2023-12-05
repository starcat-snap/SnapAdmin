<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\EmailFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class EmailField extends StringField
{
    protected function getSerializerClass(): string
    {
        return EmailFieldSerializer::class;
    }
}
