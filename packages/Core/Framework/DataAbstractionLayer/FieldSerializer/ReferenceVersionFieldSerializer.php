<?php
declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer;

use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Uuid\Uuid;

/**
 * @internal
 */
#[Package('core')]
class ReferenceVersionFieldSerializer implements FieldSerializerInterface
{
    public function normalize(Field $field, array $data, WriteParameterBag $parameters): array
    {
        if (!$field instanceof ReferenceVersionField) {
            throw DataAbstractionLayerException::invalidSerializerField(ReferenceVersionField::class, $field);
        }

        $value = $data[$field->getPropertyName()] ?? null;
        if ($value === null && !$field->is(Required::class)) {
            return $data;
        }

        $definition = $parameters->getDefinition();

        $reference = $field->getVersionReferenceDefinition();

        $context = $parameters->getContext();

        if ($value !== null || $definition === $reference) {
            // parent inheritance with versioning
            $value ??= Defaults::LIVE_VERSION;
        } elseif ($context->has($reference->getEntityName(), 'versionId')) {
            // if the reference is already written, use the version id of the written entity
            $value = $context->get($reference->getEntityName(), 'versionId');
        } elseif ($definition->getParentDefinition() === $reference && $context->has($definition->getEntityName(), 'versionId')) {
            // if the current entity is a sub entity (e.g. order -> line-item)
            // and the version id isn't set, use the same version id of the own entity
            // this is the case, if a entity is created over a sub api call
            $value = $context->get($definition->getEntityName(), 'versionId');
        } else {
            $value = Defaults::LIVE_VERSION;
        }

        $data[$field->getPropertyName()] = $value;

        return $data;
    }

    public function encode(
        Field             $field,
        EntityExistence   $existence,
        KeyValuePair      $data,
        WriteParameterBag $parameters
    ): \Generator
    {
        if (!$field instanceof ReferenceVersionField) {
            throw DataAbstractionLayerException::invalidSerializerField(ReferenceVersionField::class, $field);
        }

        $definition = $parameters->getDefinition();

        $reference = $field->getVersionReferenceDefinition();

        $context = $parameters->getContext();

        if ($data->getValue() !== null || $definition === $reference) {
            // parent inheritance with versioning
            $value = $data->getValue() ?? Defaults::LIVE_VERSION;
        } elseif ($context->has($reference->getEntityName(), 'versionId')) {
            // if the reference is already written, use the version id of the written entity
            $value = $context->get($reference->getEntityName(), 'versionId');
        } elseif ($definition->getParentDefinition() === $reference && $context->has($definition->getEntityName(), 'versionId')) {
            // if the current entity is a sub entity (e.g. order -> line-item)
            // and the version id isn't set, use the same version id of the own entity
            // this is the case, if a entity is created over a sub api call
            $value = $context->get($definition->getEntityName(), 'versionId');
        } else {
            $value = Defaults::LIVE_VERSION;
        }

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
