<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @codeCoverageIgnore
 *
 * @extends Collection<CartPositionStruct>
 */
#[Package('services-settings')]
class CartPositionCollection extends Collection
{
    public function __construct(iterable $elements = [])
    {
        foreach ($elements as $element) {
            if (\is_array($element)) {
                $element = $this->getElementFromArray($element);
            }

            $this->add($element);
        }
    }

    protected function getExpectedClass(): ?string
    {
        return CartPositionStruct::class;
    }

    protected function getElementFromArray(array $element): CartPositionStruct
    {
        return CartPositionStruct::fromArray($element);
    }
}
