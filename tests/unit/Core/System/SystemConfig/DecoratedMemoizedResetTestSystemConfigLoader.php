<?php declare(strict_types=1);

namespace SnapAdmin\Tests\Unit\Core\System\SystemConfig;

use SnapAdmin\Core\System\SystemConfig\AbstractSystemConfigLoader;

/**
 * @internal
 */
class DecoratedMemoizedResetTestSystemConfigLoader extends AbstractSystemConfigLoader
{
    public function __construct(private readonly AbstractSystemConfigLoader $decorated)
    {
    }

    public function getDecorated(): AbstractSystemConfigLoader
    {
        return $this->decorated;
    }

    /**
     * @return array<string, mixed>
     */
    public function load(?string $scopeId): array
    {
        return $this->getDecorated()->load($scopeId);
    }
}
