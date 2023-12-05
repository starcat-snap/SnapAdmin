<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @codeCoverageIgnore
 * Pseudo immutable collection
 *
 * @extends Collection<PluginCategoryStruct>
 */
#[Package('services-settings')]
final class PluginCategoryCollection extends Collection
{
    public function getApiAlias(): string
    {
        return 'store_category_collection';
    }

    protected function getExpectedClass(): string
    {
        return PluginCategoryStruct::class;
    }
}
