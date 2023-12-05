<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @codeCoverageIgnore
 *
 * @template-extends StoreCollection<ImageStruct>
 */
#[Package('services-settings')]
class ImageCollection extends StoreCollection
{
    protected function getExpectedClass(): ?string
    {
        return ImageStruct::class;
    }

    protected function getElementFromArray(array $element): StoreStruct
    {
        return ImageStruct::fromArray($element);
    }
}
