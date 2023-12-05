<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Facade;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\RequestCriteriaBuilder;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Script\Exception\HookInjectionException;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\HookServiceFactory;
use SnapAdmin\Core\Framework\Script\Execution\Awareness\SalesChannelContextAware;
use SnapAdmin\Core\Framework\Script\Execution\Hook;
use SnapAdmin\Core\Framework\Script\Execution\Script;
use SnapAdmin\Core\System\SalesChannel\Entity\SalesChannelDefinitionInstanceRegistry;

/**
 * @internal
 */
#[Package('core')]
class SalesChannelRepositoryFacadeHookFactory extends HookServiceFactory
{
    /**
     * @internal
     */
    public function __construct(
        private readonly SalesChannelDefinitionInstanceRegistry $registry,
        private readonly RequestCriteriaBuilder $criteriaBuilder
    ) {
    }

    public function factory(Hook $hook, Script $script): SalesChannelRepositoryFacade
    {
        if (!$hook instanceof SalesChannelContextAware) {
            throw new HookInjectionException($hook, self::class, SalesChannelContextAware::class);
        }

        return new SalesChannelRepositoryFacade(
            $this->registry,
            $this->criteriaBuilder,
            $hook->getSalesChannelContext()
        );
    }

    public function getName(): string
    {
        return 'store';
    }
}
