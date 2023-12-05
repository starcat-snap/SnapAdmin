<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @codeCoverageIgnore
 * Pseudo immutable collection
 *
 * @extends Collection<StorePluginStruct>
 */
#[Package('services-settings')]
final class PluginRecommendationCollection extends Collection
{
    public function __construct(iterable $elements = [])
    {
        parent::__construct();

        $this->elements = [];
        foreach ($elements as $element) {
            $this->validateType($element);
            $this->elements[] = $element;
        }
    }

    public function add($element): void
    {
        // disallow add
    }

    public function set($key, $element): void
    {
        // disallow set
    }

    public function sort(\Closure $closure): void
    {
        // disallow sorting
    }

    public function getApiAlias(): string
    {
        return 'store_plugin_recommendation_collection';
    }

    protected function getExpectedClass(): string
    {
        return StorePluginStruct::class;
    }
}
