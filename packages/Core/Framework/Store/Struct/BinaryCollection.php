<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @codeCoverageIgnore
 *
 * @template-extends StoreCollection<BinaryStruct>
 */
#[Package('services-settings')]
class BinaryCollection extends StoreCollection
{
    protected function getExpectedClass(): ?string
    {
        return BinaryStruct::class;
    }

    protected function getElementFromArray(array $element): StoreStruct
    {
        return BinaryStruct::fromArray($element);
    }
}
