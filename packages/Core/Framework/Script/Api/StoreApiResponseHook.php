<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script\Api;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Facade\RepositoryFacadeHookFactory;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Facade\RepositoryWriterFacadeHookFactory;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Facade\SalesChannelRepositoryFacadeHookFactory;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Routing\Facade\RequestFacadeFactory;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\SalesChannelContextAware;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\ScriptResponseAwareTrait;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\StoppableHook;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\StoppableHookTrait;
use SnapAdmin\Core\Framework\Script\Execution\FunctionHook;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;
use SnapAdmin\Core\System\SystemConfig\Facade\SystemConfigFacadeHookFactory;

/**
 * Triggered when the api endpoint /store-api/script/{hook} is called. Used to provide the HTTP-Response.
 * This function is only called when no response for the provided cache key is cached, or no `cache_key` function implemented.
 *
 * @hook-use-case custom_endpoint
 *
 * @since 6.4.9.0
 *
 * @final
 */
#[Package('core')]
class StoreApiResponseHook extends FunctionHook implements SalesChannelContextAware, StoppableHook
{
    use ScriptResponseAwareTrait;
    use StoppableHookTrait;

    final public const FUNCTION_NAME = 'response';

    /**
     * @param array<mixed> $request
     * @param array<mixed> $query
     */
    public function __construct(
        private readonly string $name,
        private readonly array $request,
        private readonly array $query,
        private readonly SalesChannelContext $salesChannelContext
    ) {
        parent::__construct($salesChannelContext->getContext());
    }

    /**
     * @return array<mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * @return array<mixed>
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    public function getSalesChannelContext(): SalesChannelContext
    {
        return $this->salesChannelContext;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFunctionName(): string
    {
        return self::FUNCTION_NAME;
    }

    public static function getServiceIds(): array
    {
        return [
            RepositoryFacadeHookFactory::class,
            SystemConfigFacadeHookFactory::class,
            SalesChannelRepositoryFacadeHookFactory::class,
            RepositoryWriterFacadeHookFactory::class,
            ScriptResponseFactoryFacadeHookFactory::class,
            RequestFacadeFactory::class,
        ];
    }
}
