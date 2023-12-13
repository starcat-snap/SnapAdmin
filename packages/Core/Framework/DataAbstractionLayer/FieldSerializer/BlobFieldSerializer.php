<?php
declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BlobField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class BlobFieldSerializer implements FieldSerializerInterface
{
    public function normalize(Field $field, array $data, WriteParameterBag $parameters): array
    {
        return $data;
    }

    public function encode(
        Field $field,
        EntityExistence $existence,
        KeyValuePair $data,
        WriteParameterBag $parameters
    ): \Generator {
        if (!$field instanceof BlobField) {
            throw throw DataAbstractionLayerException::invalidSerializerField(BlobField::class, $field);
        }

        yield $field->getStorageName() => $data->getValue();
    }

    public function decode(Field $field, mixed $value): ?string
    {
        if ($value === null) {
            return $value;
        }

        return (string) $value;
    }
}
