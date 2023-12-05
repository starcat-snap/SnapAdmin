<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @codeCoverageIgnore
 * Pseudo immutable collection
 *
 * @extends Collection<PluginRegionStruct>
 */
#[Package('services-settings')]
final class PluginRegionCollection extends Collection
{
    public function getApiAlias(): string
    {
        return 'store_plugin_region_collection';
    }

    protected function getExpectedClass(): string
    {
        return PluginRegionStruct::class;
    }
}
