<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Aggregate\PluginTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<PluginTranslationEntity>
 */
#[Package('core')]
class PluginTranslationCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'plugin_translation_collection';
    }

    protected function getExpectedClass(): string
    {
        return PluginTranslationEntity::class;
    }
}
