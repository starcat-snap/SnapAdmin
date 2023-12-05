<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Facade;

use SnapAdmin\Core\Framework\Api\Acl\AclCriteriaValidator;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\RequestCriteriaBuilder;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\HookServiceFactory;
use SnapAdmin\Core\Framework\Script\Execution\Hook;
use SnapAdmin\Core\Framework\Script\Execution\Script;

/**
 * @internal
 */
#[Package('core')]
class RepositoryFacadeHookFactory extends HookServiceFactory
{
    /**
     * @internal
     */
    public function __construct(
        private readonly DefinitionInstanceRegistry $registry,
        private readonly AppContextCreator $appContextCreator,
        private readonly RequestCriteriaBuilder $criteriaBuilder,
        private readonly AclCriteriaValidator $criteriaValidator
    ) {
    }

    public function factory(Hook $hook, Script $script): RepositoryFacade
    {
        return new RepositoryFacade(
            $this->registry,
            $this->criteriaBuilder,
            $this->criteriaValidator,
            $this->appContextCreator->getAppContext($hook, $script)
        );
    }

    public function getName(): string
    {
        return 'repository';
    }
}
