<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing\MessageQueue;

use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\AsyncMessageInterface;

#[Package('core')]
class IterateEntityIndexerMessage implements AsyncMessageInterface
{
    /**
     * @var string
     */
    protected $indexer;

    /**
     * @param array{offset: int|null}|null $offset
     * @param array<string> $skip
     * @internal
     *
     * @deprecated tag:v6.6.0 - parameter $offset will be natively typed to type `?array`
     *
     */
    public function __construct(
        string          $indexer,
        protected       $offset,
        protected array $skip = []
    )
    {
        $this->indexer = $indexer;
    }

    public function getIndexer(): string
    {
        return $this->indexer;
    }

    /**
     * @return array{offset: int|null}|null
     * @deprecated tag:v6.6.0 - reason:return-type-change - return type will be natively typed to type `?array`
     *
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param array{offset: int|null}|null $offset
     * @deprecated tag:v6.6.0 - parameter $offset will be natively typed to type `?array`
     *
     */
    public function setOffset($offset): void
    {
        if ($offset !== null && !\is_array($offset)) {
            Feature::triggerDeprecationOrThrow(
                'v6.6.0.0',
                'The parameter $offset of method ' . __METHOD__ . ' will be natively typed to type `?array` in v6.6.0.0.'
            );
        }

        $this->offset = $offset;
    }

    /**
     * @return array<string>
     */
    public function getSkip(): array
    {
        return $this->skip;
    }
}
