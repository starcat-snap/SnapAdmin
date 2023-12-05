<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer;

use SnapAdmin\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use SnapAdmin\Core\Checkout\Cart\Price\Struct\ListPrice;
use SnapAdmin\Core\Checkout\Cart\Price\Struct\ReferencePrice;
use SnapAdmin\Core\Checkout\Cart\Tax\Struct\CalculatedTax;
use SnapAdmin\Core\Checkout\Cart\Tax\Struct\CalculatedTaxCollection;
use SnapAdmin\Core\Checkout\Cart\Tax\Struct\TaxRule;
use SnapAdmin\Core\Checkout\Cart\Tax\Struct\TaxRuleCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class CalculatedPriceFieldSerializer extends JsonFieldSerializer
{
    public function encode(
        Field             $field,
        EntityExistence   $existence,
        KeyValuePair      $data,
        WriteParameterBag $parameters
    ): \Generator
    {
        $value = json_decode(json_encode($data->getValue(), \JSON_PRESERVE_ZERO_FRACTION | \JSON_THROW_ON_ERROR), true, 512, \JSON_THROW_ON_ERROR);

        unset($value['extensions']);
        if (isset($value['listPrice'])) {
            unset($value['listPrice']['extensions']);
        }

        $data->setValue($value);

        yield from parent::encode($field, $existence, $data, $parameters);
    }

    public function decode(Field $field, mixed $value): ?CalculatedPrice
    {
        if ($value === null) {
            return null;
        }

        $decoded = parent::decode($field, $value);
        if (!\is_array($decoded)) {
            return null;
        }

        $taxRules = array_map(
            fn(array $tax) => new TaxRule(
                (float)$tax['taxRate'],
                (float)$tax['percentage']
            ),
            $decoded['taxRules']
        );

        $calculatedTaxes = array_map(
            fn(array $tax) => new CalculatedTax(
                (float)$tax['tax'],
                (float)$tax['taxRate'],
                (float)$tax['price']
            ),
            $decoded['calculatedTaxes']
        );

        $referencePriceDefinition = null;
        if (isset($decoded['referencePrice'])) {
            $refPrice = $decoded['referencePrice'];

            $referencePriceDefinition = new ReferencePrice(
                $refPrice['price'],
                $refPrice['purchaseUnit'],
                $refPrice['referenceUnit'],
                $refPrice['unitName']
            );
        }

        $listPrice = null;
        if (isset($decoded['listPrice'])) {
            $listPrice = ListPrice::createFromUnitPrice(
                (float)$decoded['unitPrice'],
                (float)$decoded['listPrice']['price']
            );
        }

        return new CalculatedPrice(
            (float)$decoded['unitPrice'],
            (float)$decoded['totalPrice'],
            new CalculatedTaxCollection($calculatedTaxes),
            new TaxRuleCollection($taxRules),
            (int)$decoded['quantity'],
            $referencePriceDefinition,
            $listPrice
        );
    }
}
