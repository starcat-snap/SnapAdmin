<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache\InvalidatorStorage;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class RedisInvalidatorStorage extends AbstractInvalidatorStorage
{
    private const KEY = 'invalidation';

    /**
     * @param \Redis|\RedisCluster $redis
     *
     * @internal
     */
    public function __construct(private $redis)
    {
    }

    public function store(array $tags): void
    {
        $this->redis->sAdd(self::KEY, ...$tags);
    }

    public function loadAndDelete(): array
    {
        /** @var array{0: list<string>, 1: mixed} $values */
        $values = $this
            ->redis
            ->multi()
            ->sMembers(self::KEY)
            ->del(self::KEY)
            ->exec();

        return $values[0];
    }
}
