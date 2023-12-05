<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
use Symfony\Component\Cache\Marshaller\MarshallerInterface;

/**
 * @internal
 */
#[Package('core')]
class SnapAdminRedisTagAwareAdapter extends RedisTagAwareAdapter
{
    public function __construct(
        $redis,
        string $namespace = '',
        int $defaultLifetime = 0,
        ?MarshallerInterface $marshaller = null,
        ?string $prefix = null
    )
    {
        parent::__construct($redis, $prefix . $namespace, $defaultLifetime, $marshaller);
    }
}
