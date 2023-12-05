<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer;

use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\PriceField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Pricing\Price;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Pricing\PriceCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Util\Json;
use SnapAdmin\Core\Framework\Validation\Constraint\Uuid;
use SnapAdmin\Core\Framework\Validation\WriteConstraintViolationException;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @internal
 */
#[Package('core')]
class PriceFieldSerializer extends AbstractFieldSerializer
{
    public function encode(
        Field $field,
        EntityExistence $existence,
        KeyValuePair $data,
        WriteParameterBag $parameters
    ): \Generator {
        if (!$field instanceof PriceField) {
            throw DataAbstractionLayerException::invalidSerializerField(PriceField::class, $field);
        }

        $value = $data->getValue();

        if ($this->requiresValidation($field, $existence, $value, $parameters)) {
            if ($value !== null) {
                foreach ($value as &$row) {
                    unset($row['extensions']);
                }
            }
            $data->setValue($value);

            if ($field->is(Required::class)) {
                $this->validate([new NotBlank()], $data, $parameters->getPath());
            }

            $constraints = $this->getCachedConstraints($field);
            $pricePath = $parameters->getPath() . '/' . $field->getPropertyName();

            foreach ($data->getValue() as $index => $price) {
                $this->validate($constraints, new KeyValuePair((string) $index, $price, true), $pricePath);
            }

            $this->ensureDefaultPrice($parameters, $data->getValue(), $field->getPropertyName());

            $converted = [];

            foreach ($value as $price) {
                $price['gross'] = (float) $price['gross'];
                $price['net'] = (float) $price['net'];

                if (isset($price['listPrice'])) {
                    $price['percentage'] = null;
                } elseif (\array_key_exists('percentage', $price)) {
                    unset($price['percentage']);
                }

                if (($price['listPrice']['net'] ?? 0) > 0 || ($price['listPrice']['gross'] ?? 0) > 0) {
                    $price['percentage'] = [
                        'net' => 0.0,
                        'gross' => 0.0,
                    ];

                    if (($price['listPrice']['net'] ?? 0) > 0) {
                        $price['percentage']['net'] = round(100 - $price['net'] / $price['listPrice']['net'] * 100, 2);
                    }

                    if (($price['listPrice']['gross'] ?? 0) > 0) {
                        $price['percentage']['gross'] = round(100 - $price['gross'] / $price['listPrice']['gross'] * 100, 2);
                    }
                }

                $converted['c' . $price['currencyId']] = $price;
            }
            $value = $converted;
        }

        if ($value !== null) {
            $value = Json::encode($value);
        }

        yield $field->getStorageName() => $value;
    }

    public function decode(Field $field, mixed $value): ?PriceCollection
    {
        if ($value === null) {
            return null;
        }

        // used for nested hydration (example cheapest-price-hydrator)
        if (\is_string($value)) {
            $value = json_decode($value, true, 512, \JSON_THROW_ON_ERROR);
        }

        $collection = new PriceCollection();

        foreach ($value as $row) {
            if ((!isset($row['listPrice']) || !isset($row['listPrice']['gross'])) && (!isset($row['regulationPrice']) || !isset($row['regulationPrice']['gross']))) {
                $collection->add(
                    new Price($row['currencyId'], (float) $row['net'], (float) $row['gross'], (bool) $row['linked'])
                );

                continue;
            }

            $listPrice = $regulationPrice = null;
            if (isset($row['listPrice']) && isset($row['listPrice']['gross'])) {
                $data = $row['listPrice'];
                $listPrice = new Price(
                    $row['currencyId'],
                    (float) $data['net'],
                    (float) $data['gross'],
                    (bool) $data['linked'],
                );
            }

            if (isset($row['regulationPrice']) && isset($row['regulationPrice']['gross'])) {
                $data = $row['regulationPrice'];
                $regulationPrice = new Price(
                    $row['currencyId'],
                    (float) $data['net'],
                    (float) $data['gross'],
                    (bool) $data['linked'],
                );
            }

            $collection->add(
                new Price(
                    $row['currencyId'],
                    (float) $row['net'],
                    (float) $row['gross'],
                    (bool) $row['linked'],
                    $listPrice,
                    $row['percentage'] ?? null,
                    $regulationPrice
                )
            );
        }

        return $collection;
    }

    protected function getConstraints(Field $field): array
    {
        $constraints = [
            new Collection([
                'allowExtraFields' => true,
                'allowMissingFields' => false,
                'fields' => [
                    'currencyId' => [new NotBlank(), new Uuid()],
                    'gross' => [new NotBlank(), new Type(['numeric'])],
                    'net' => [new NotBlank(), new Type(['numeric'])],
                    'linked' => [new Type('boolean')],
                    'listPrice' => [
                        new Optional(
                            new Collection([
                                'allowExtraFields' => true,
                                'allowMissingFields' => false,
                                'fields' => [
                                    'gross' => [new NotBlank(), new Type(['numeric'])],
                                    'net' => [new NotBlank(), new Type('numeric')],
                                    'linked' => [new Type('boolean')],
                                ],
                            ])
                        ),
                    ],
                    'regulationPrice' => [
                        new Optional(
                            new Collection([
                                'allowExtraFields' => true,
                                'allowMissingFields' => false,
                                'fields' => [
                                    'gross' => [new NotBlank(), new Type(['numeric'])],
                                    'net' => [new NotBlank(), new Type('numeric')],
                                    'linked' => [new Type('boolean')],
                                ],
                            ])
                        ),
                    ],
                ],
            ]),
        ];

        return $constraints;
    }

    /**
     * @param array<array<string, mixed>> $prices
     */
    private function ensureDefaultPrice(WriteParameterBag $parameters, array $prices, string $propertyName): void
    {
        foreach ($prices as $price) {
            if ($price['currencyId'] === Defaults::CURRENCY) {
                return;
            }
        }

        $violationList = new ConstraintViolationList();
        $violationList->add(
            new ConstraintViolation(
                'No price for default currency defined',
                'No price for default currency defined',
                [],
                '',
                '/' . $propertyName,
                $prices
            )
        );

        throw new WriteConstraintViolationException($violationList, $parameters->getPath());
    }
}
