<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Sync;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class SyncBehavior
{
    /**
     * @param list<string> $skipIndexers
     */
    public function __construct(
        protected ?string $indexingBehavior = null,
        protected array $skipIndexers = []
    ) {
    }

    public function getIndexingBehavior(): ?string
    {
        return $this->indexingBehavior;
    }

    /**
     * @return list<string>
     */
    public function getSkipIndexers(): array
    {
        return $this->skipIndexers;
    }
}
