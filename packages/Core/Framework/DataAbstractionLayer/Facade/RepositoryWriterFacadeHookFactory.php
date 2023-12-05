<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Facade;

use SnapAdmin\Core\Framework\Api\Sync\SyncService;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\HookServiceFactory;
use SnapAdmin\Core\Framework\Script\Execution\Hook;
use SnapAdmin\Core\Framework\Script\Execution\Script;

/**
 * @internal
 */
#[Package('core')]
class RepositoryWriterFacadeHookFactory extends HookServiceFactory
{
    public function __construct(
        private readonly DefinitionInstanceRegistry $registry,
        private readonly AppContextCreator          $appContextCreator,
        private readonly SyncService                $syncService
    )
    {
    }

    public function factory(Hook $hook, Script $script): RepositoryWriterFacade
    {
        return new RepositoryWriterFacade(
            $this->registry,
            $this->syncService,
            $this->appContextCreator->getAppContext($hook, $script)
        );
    }

    public function getName(): string
    {
        return 'writer';
    }
}
