<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class UpdatedAtFieldSerializer extends DateTimeFieldSerializer
{
    /**
     * @throws DataAbstractionLayerException
     */
    public function encode(
        Field             $field,
        EntityExistence   $existence,
        KeyValuePair      $data,
        WriteParameterBag $parameters
    ): \Generator
    {
        if (!$field instanceof UpdatedAtField) {
            throw DataAbstractionLayerException::invalidSerializerField(UpdatedAtField::class, $field);
        }
        if (!$existence->exists()) {
            return;
        }

        $data->setValue(new \DateTime());

        yield from parent::encode($field, $existence, $data, $parameters);
    }
}
