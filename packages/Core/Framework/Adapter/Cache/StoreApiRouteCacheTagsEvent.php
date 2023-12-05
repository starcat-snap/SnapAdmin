<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;
use SnapAdmin\Core\System\SalesChannel\StoreApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('core')]
class StoreApiRouteCacheTagsEvent extends Event
{
    public function __construct(
        protected array $tags,
        protected Request $request,
        private readonly StoreApiResponse $response,
        protected SalesChannelContext $context,
        protected ?Criteria $criteria
    ) {
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getContext(): SalesChannelContext
    {
        return $this->context;
    }

    public function getCriteria(): ?Criteria
    {
        return $this->criteria;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    public function addTags(array $tags): void
    {
        $this->tags = array_merge($this->tags, $tags);
    }

    public function getSalesChannelId(): string
    {
        return $this->context->getSalesChannelId();
    }

    public function getResponse(): StoreApiResponse
    {
        return $this->response;
    }
}
