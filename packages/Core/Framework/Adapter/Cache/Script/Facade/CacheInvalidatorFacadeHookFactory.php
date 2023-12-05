<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache\Script\Facade;

use SnapAdmin\Core\Framework\Adapter\Cache\CacheInvalidator;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\HookServiceFactory;
use SnapAdmin\Core\Framework\Script\Execution\Hook;
use SnapAdmin\Core\Framework\Script\Execution\Script;

/**
 * @internal
 */
#[Package('core')]
class CacheInvalidatorFacadeHookFactory extends HookServiceFactory
{
    public function __construct(private readonly CacheInvalidator $cacheInvalidator)
    {
    }

    public function factory(Hook $hook, Script $script): CacheInvalidatorFacade
    {
        return new CacheInvalidatorFacade($this->cacheInvalidator);
    }

    public function getName(): string
    {
        return 'cache';
    }
}
