<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<PluginEntity>
 */
#[Package('core')]
class PluginCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'plugin_collection';
    }

    protected function getExpectedClass(): string
    {
        return PluginEntity::class;
    }
}
