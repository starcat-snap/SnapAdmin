<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('core')]
class InvalidateCacheEvent extends Event
{
    public function __construct(protected array $keys)
    {
    }

    public function getKeys(): array
    {
        return $this->keys;
    }
}
