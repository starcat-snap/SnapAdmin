<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Core\Event;

use SnapAdmin\Core\Content\Media\Core\Params\ThumbnailLocationStruct;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * The event is dispatched, when location for a thumbnail should be generated afterward and can be used
 * to extend the data which is required for this process.
 *
 * @implements \IteratorAggregate<array-key, ThumbnailLocationStruct>
 */
#[Package('content')]
class ThumbnailLocationEvent implements \IteratorAggregate
{
    /**
     * @param array<string, ThumbnailLocationStruct> $locations
     */
    public function __construct(public array $locations)
    {
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->locations);
    }
}
