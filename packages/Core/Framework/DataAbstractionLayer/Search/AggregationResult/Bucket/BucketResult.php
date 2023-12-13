<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Bucket;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use SnapAdmin\Core\Framework\Log\Package;


#[Package('core')]
class BucketResult extends AggregationResult
{
    /**
     * @param list<Bucket> $buckets
     */
    public function __construct(
        string          $name,
        protected array $buckets
    )
    {
        parent::__construct($name);
    }

    /**
     * @return list<Bucket>
     */
    public function getBuckets(): array
    {
        return $this->buckets;
    }

    public function sort(\Closure $closure): void
    {
        usort($this->buckets, $closure);
    }

    /**
     * @return list<string>
     */
    public function getKeys(): array
    {
        $keys = [];
        foreach ($this->buckets as $bucket) {
            $keys[] = $bucket->getKey();
        }

        return array_values(array_filter($keys));
    }

    public function has(?string $key): bool
    {
        $exists = $this->get($key);

        return $exists !== null;
    }

    public function get(?string $key): ?Bucket
    {
        foreach ($this->buckets as $bucket) {
            if ($bucket->getKey() === $key) {
                return $bucket;
            }
        }

        return null;
    }
}
