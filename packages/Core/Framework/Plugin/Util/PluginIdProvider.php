<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Util;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class PluginIdProvider
{
    /**
     * @internal
     */
    public function __construct(private readonly EntityRepository $pluginRepo)
    {
    }

    public function getPluginIdByBaseClass(string $pluginBaseClassName, Context $context): string
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('baseClass', $pluginBaseClassName));
        /** @var string $id */
        $id = $this->pluginRepo->searchIds($criteria, $context)->firstId();

        return $id;
    }
}
