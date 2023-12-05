<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @codeCoverageIgnore
 */
#[Package('services-settings')]
class PluginRegionStruct extends Struct
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var PluginCategoryCollection
     */
    protected $categories;

    public function __construct(
        string   $name,
        string   $label,
        iterable $categories
    )
    {
        $this->name = $name;
        $this->label = $label;
        $this->categories = new PluginCategoryCollection($categories);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getCategories(): PluginCategoryCollection
    {
        return $this->categories;
    }

    public function getApiAlias(): string
    {
        return 'store_plugin_region';
    }
}
