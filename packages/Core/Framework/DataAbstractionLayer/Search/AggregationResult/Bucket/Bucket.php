<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Bucket;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\AggregationResult;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @final
 */
#[Package('core')]
class Bucket extends Struct
{
    public function __construct(
        protected ?string            $key,
        protected int                $count,
        protected ?AggregationResult $result
    )
    {
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getResult(): ?AggregationResult
    {
        return $this->result;
    }

    public function jsonSerialize(): array
    {
        $data = get_object_vars($this);

        if ($this->result) {
            $data[$this->result->getName()] = $data['result'];
        }
        unset($data['result']);

        return $data;
    }

    public function incrementCount(int $count): void
    {
        $this->count += $count;
    }

    public function getApiAlias(): string
    {
        return 'aggregation_bucket';
    }
}
