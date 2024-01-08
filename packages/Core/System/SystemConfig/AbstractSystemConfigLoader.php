<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('system-settings')]
abstract class AbstractSystemConfigLoader
{
    abstract public function getDecorated(): AbstractSystemConfigLoader;

    abstract public function load(?string $scopeId): array;
}
