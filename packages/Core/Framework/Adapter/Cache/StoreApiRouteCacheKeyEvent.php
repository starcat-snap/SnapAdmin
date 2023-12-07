<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SaagFrontend\Channel\ChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('core')]
class StoreApiRouteCacheKeyEvent extends Event
{
    private bool $disableCaching = false;

    /**
     * @param array<mixed> $parts
     */
    public function __construct(
        protected array               $parts,
        protected Request             $request,
        protected ChannelContext $context,
        protected ?Criteria           $criteria
    )
    {
    }

    /**
     * @return array<mixed>
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getContext(): ChannelContext
    {
        return $this->context;
    }

    public function getCriteria(): ?Criteria
    {
        return $this->criteria;
    }

    /**
     * @param array<int, bool|string> $parts
     */
    public function setParts(array $parts): void
    {
        $this->parts = $parts;
    }

    public function addPart(string $part): void
    {
        $this->parts[] = $part;
    }

    public function getChannelId(): string
    {
        return $this->context->getChannelId();
    }

    public function disableCaching(): void
    {
        $this->disableCaching = true;
    }

    public function shouldCache(): bool
    {
        return !$this->disableCaching;
    }
}
