<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script\Api;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\HookServiceFactory;
use SnapAdmin\Core\Framework\Script\Execution\Hook;
use SnapAdmin\Core\Framework\Script\Execution\Script;
use Symfony\Component\Routing\RouterInterface;

/**
 * @internal
 */
#[Package('core')]
class ScriptResponseFactoryFacadeHookFactory extends HookServiceFactory
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }

    public function factory(Hook $hook, Script $script): ScriptResponseFactoryFacade
    {
        return new ScriptResponseFactoryFacade(
            $this->router,
        );
    }

    public function getName(): string
    {
        return 'response';
    }
}
