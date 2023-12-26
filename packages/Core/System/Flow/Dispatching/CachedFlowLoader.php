<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching;

use SnapAdmin\Core\Framework\Adapter\Cache\CacheValueCompressor;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Flow\FlowEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * @internal not intended for decoration or replacement
 */
#[Package('services-settings')]
class CachedFlowLoader extends AbstractFlowLoader implements EventSubscriberInterface, ResetInterface
{
    final public const KEY = 'flow-loader';

    private array $flows = [];

    public function __construct(
        private readonly AbstractFlowLoader $decorated,
        private readonly CacheInterface $cache
    ) {
    }

    /**
     * @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FlowEvents::FLOW_WRITTEN_EVENT => 'invalidate',
        ];
    }

    public function load(): array
    {
        if (!empty($this->flows)) {
            return $this->flows;
        }

        $value = $this->cache->get(self::KEY, function (ItemInterface $item) {
            $item->tag([self::KEY]);

            return CacheValueCompressor::compress($this->decorated->load());
        });

        return $this->flows = CacheValueCompressor::uncompress($value);
    }

    public function invalidate(): void
    {
        $this->reset();
        $this->cache->delete(self::KEY);
    }

    public function reset(): void
    {
        $this->flows = [];
    }
}
