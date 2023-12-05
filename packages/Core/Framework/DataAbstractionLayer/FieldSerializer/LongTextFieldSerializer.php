<?php
declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\AllowEmptyString;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Util\HtmlSanitizer;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 */
#[Package('core')]
class LongTextFieldSerializer extends AbstractFieldSerializer
{
    /**
     * @internal
     */
    public function __construct(
        ValidatorInterface             $validator,
        DefinitionInstanceRegistry     $definitionRegistry,
        private readonly HtmlSanitizer $sanitizer
    )
    {
        parent::__construct($validator, $definitionRegistry);
    }

    public function encode(
        Field             $field,
        EntityExistence   $existence,
        KeyValuePair      $data,
        WriteParameterBag $parameters
    ): \Generator
    {
        if (!$field instanceof LongTextField) {
            throw DataAbstractionLayerException::invalidSerializerField(LongTextField::class, $field);
        }

        if ($data->getValue() === '' && !$field->is(AllowEmptyString::class)) {
            $data->setValue(null);
        }

        $this->validateIfNeeded($field, $existence, $data, $parameters);

        $data->setValue($this->sanitize($this->sanitizer, $data, $field, $existence));

        $this->validateIfNeeded($field, $existence, $data, $parameters);

        yield $field->getStorageName() => $data->getValue() !== null ? (string)$data->getValue() : null;
    }

    public function decode(Field $field, mixed $value): ?string
    {
        if ($value === null) {
            return $value;
        }

        return (string)$value;
    }

    protected function getConstraints(Field $field): array
    {
        $constraints = [
            new Type('string'),
        ];

        if (!$field->is(AllowEmptyString::class)) {
            $constraints[] = new NotBlank();
        }

        if ($field->is(AllowEmptyString::class) && $field->is(Required::class)) {
            $constraints[] = new NotNull();
        }

        return $constraints;
    }
}
