<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Context;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Migration\MigrationCollection;
use SnapAdmin\Core\Framework\Plugin;

#[Package('core')]
class UninstallContext extends InstallContext
{
    public function __construct(
        Plugin $plugin,
        Context $context,
        string $currentSnapAdminVersion,
        string $currentPluginVersion,
        MigrationCollection $migrationCollection,
        private readonly bool $keepUserData
    ) {
        parent::__construct($plugin, $context, $currentSnapAdminVersion, $currentPluginVersion, $migrationCollection);
    }

    /**
     * If true is returned, migrations of the plugin will also be removed
     */
    public function keepUserData(): bool
    {
        return $this->keepUserData;
    }
}
