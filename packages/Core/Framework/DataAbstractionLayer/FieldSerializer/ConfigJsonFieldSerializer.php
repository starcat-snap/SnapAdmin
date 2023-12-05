<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ConfigJsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class ConfigJsonFieldSerializer extends JsonFieldSerializer
{
    public function encode(Field $field, EntityExistence $existence, KeyValuePair $data, WriteParameterBag $parameters): \Generator
    {
        if (!$field instanceof ConfigJsonField) {
            throw DataAbstractionLayerException::invalidSerializerField(ConfigJsonField::class, $field);
        }

        $wrapped = [ConfigJsonField::STORAGE_KEY => $data->getValue()];
        $data->setValue($wrapped);

        return parent::encode($field, $existence, $data, $parameters);
    }

    public function decode(Field $field, mixed $value): mixed
    {
        if (!$field instanceof ConfigJsonField) {
            throw DataAbstractionLayerException::invalidSerializerField(ConfigJsonField::class, $field);
        }

        $wrapped = parent::decode($field, $value);
        if ($wrapped === null || !isset($wrapped[ConfigJsonField::STORAGE_KEY])) {
            return null;
        }

        return $wrapped[ConfigJsonField::STORAGE_KEY];
    }
}
