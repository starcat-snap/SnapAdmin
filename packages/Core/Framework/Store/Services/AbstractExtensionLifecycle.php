<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Services;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('services-settings')]
abstract class AbstractExtensionLifecycle
{
    abstract public function install(string $type, string $technicalName, Context $context): void;

    abstract public function update(string $type, string $technicalName, bool $allowNewPermissions, Context $context): void;

    abstract public function uninstall(string $type, string $technicalName, bool $keepUserData, Context $context): void;

    abstract public function activate(string $type, string $technicalName, Context $context): void;

    abstract public function deactivate(string $type, string $technicalName, Context $context): void;

    abstract public function remove(string $type, string $technicalName, Context $context): void;

    abstract protected function getDecorated(): AbstractExtensionLifecycle;
}
