<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Struct;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @template TElement of Struct
 *
 * @extends Collection<TElement>
 */
#[Package('core')]
class StructCollection extends Collection
{
    public function getApiAlias(): string
    {
        return 'dal_struct_collection';
    }

    /**
     * @return class-string<Struct>
     */
    protected function getExpectedClass(): ?string
    {
        return Struct::class;
    }
}
