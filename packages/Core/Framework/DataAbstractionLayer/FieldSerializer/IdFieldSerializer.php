<?php
declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\Framework\Validation\Constraint\Uuid as UuidConstraint;

/**
 * @internal
 */
#[Package('core')]
class IdFieldSerializer extends AbstractFieldSerializer
{
    public function normalize(Field $field, array $data, WriteParameterBag $parameters): array
    {
        $key = $field->getPropertyName();
        if (!isset($data[$key])) {
            $data[$key] = Uuid::randomHex();
        }

        $parameters->getContext()->set($parameters->getDefinition()->getEntityName(), $key, $data[$key]);

        return $data;
    }

    public function encode(
        Field             $field,
        EntityExistence   $existence,
        KeyValuePair      $data,
        WriteParameterBag $parameters
    ): \Generator
    {
        if (!$field instanceof IdField) {
            throw DataAbstractionLayerException::invalidSerializerField(IdField::class, $field);
        }

        $value = $data->getValue();
        if ($value) {
            $this->validate([new UuidConstraint()], $data, $parameters->getPath());
        } elseif ($field->is(PrimaryKey::class) || $field->is(Required::class)) {
            $value = Uuid::randomHex();
        }

        if (!$value) {
            return yield $field->getStorageName() => null;
        }

        $parameters->getContext()->set($parameters->getDefinition()->getEntityName(), $data->getKey(), $value);
        yield $field->getStorageName() => Uuid::fromHexToBytes($value);
    }

    public function decode(Field $field, mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return Uuid::fromBytesToHex($value);
    }
}
