<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @codeCoverageIgnore
 *
 * @template-extends StoreCollection<VariantStruct>
 */
#[Package('services-settings')]
class VariantCollection extends StoreCollection
{
    protected function getExpectedClass(): ?string
    {
        return VariantStruct::class;
    }

    protected function getElementFromArray(array $element): StoreStruct
    {
        return VariantStruct::fromArray($element);
    }
}
