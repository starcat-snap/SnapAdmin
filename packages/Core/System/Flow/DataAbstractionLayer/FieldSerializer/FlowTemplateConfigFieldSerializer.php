<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\DataAbstractionLayer\FieldSerializer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\JsonFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Util\Json;
use SnapAdmin\Core\Framework\Validation\Constraint\Uuid;
use SnapAdmin\Core\System\Flow\DataAbstractionLayer\Field\FlowTemplateConfigField;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @internal
 */
#[Package('services-settings')]
class FlowTemplateConfigFieldSerializer extends JsonFieldSerializer
{
    public function encode(
        Field $field,
        EntityExistence $existence,
        KeyValuePair $data,
        WriteParameterBag $parameters
    ): \Generator {
        if (!$field instanceof FlowTemplateConfigField) {
            throw DataAbstractionLayerException::invalidSerializerField(FlowTemplateConfigField::class, $field);
        }

        $this->validateIfNeeded($field, $existence, $data, $parameters);

        $value = $data->getValue();

        if (!\is_array($value)) {
            yield $field->getStorageName() => null;

            return;
        }

        $value = array_merge([
            'description' => null,
            'sequences' => [],
        ], $value);

        $sequences = $value['sequences'];

        $value['sequences'] = array_map(fn ($item) => array_merge([
            'parentId' => null,
            'ruleId' => null,
            'position' => 1,
            'displayGroup' => 1,
            'trueCase' => 0,
        ], $item), $sequences);

        yield $field->getStorageName() => Json::encode($value);
    }

    protected function getConstraints(Field $field): array
    {
        return [
            new Collection([
                'allowExtraFields' => true,
                'allowMissingFields' => false,
                'fields' => [
                    'eventName' => [new NotBlank(), new Type('string')],
                    'description' => [new Type('string')],
                    'sequences' => [
                        [
                            new Optional(
                                new Collection([
                                    'allowExtraFields' => true,
                                    'allowMissingFields' => false,
                                    'fields' => [
                                        'id' => [new NotBlank(), new Uuid()],
                                        'actionName' => [new NotBlank(), new Type('string')],
                                        'parentId' => [new Uuid()],
                                        'ruleId' => [new Uuid()],
                                        'position' => [new Type('numeric')],
                                        'trueCase' => [new Type('boolean')],
                                        'displayGroup' => [new Type('numeric')],
                                        'config' => [new Type('array')],
                                    ],
                                ])
                            ),
                        ],
                    ],
                ],
            ]),
        ];
    }
}
