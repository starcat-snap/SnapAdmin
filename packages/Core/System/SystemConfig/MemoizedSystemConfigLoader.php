<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SystemConfig\Store\MemoizedSystemConfigStore;

#[Package('system-settings')]
class MemoizedSystemConfigLoader extends AbstractSystemConfigLoader
{
    /**
     * @internal
     */
    public function __construct(
        private readonly AbstractSystemConfigLoader $decorated,
        private readonly MemoizedSystemConfigStore $memoizedSystemConfigStore
    ) {
    }

    public function getDecorated(): AbstractSystemConfigLoader
    {
        return $this->decorated;
    }

    public function load(?string $scopeId, ?string $scope): array
    {
        $config = $this->memoizedSystemConfigStore->getConfig($scopeId,$scope);

        if ($config !== null) {
            return $config;
        }

        $config = $this->getDecorated()->load($scopeId,$scope);
        $this->memoizedSystemConfigStore->setConfig($scopeId,$scope, $config);

        return $config;
    }
}
