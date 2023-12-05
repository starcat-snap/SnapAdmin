<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @codeCoverageIgnore
 *
 * @template-extends StoreCollection<FaqStruct>
 */
#[Package('services-settings')]
class FaqCollection extends StoreCollection
{
    protected function getExpectedClass(): ?string
    {
        return FaqStruct::class;
    }

    protected function getElementFromArray(array $element): StoreStruct
    {
        return FaqStruct::fromArray($element);
    }
}
