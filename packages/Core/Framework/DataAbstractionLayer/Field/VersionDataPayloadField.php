<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\VersionDataPayloadFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class VersionDataPayloadField extends JsonField
{
    protected function getSerializerClass(): string
    {
        return VersionDataPayloadFieldSerializer::class;
    }
}
