<?php declare(strict_types=1);

namespace SwagManualMigrationTest;

use SnapAdmin\Core\Framework\Plugin;
use SnapAdmin\Core\Framework\Plugin\Context\ActivateContext;
use SnapAdmin\Core\Framework\Plugin\Context\DeactivateContext;
use SnapAdmin\Core\Framework\Plugin\Context\InstallContext;
use SnapAdmin\Core\Framework\Plugin\Context\UninstallContext;
use SnapAdmin\Core\Framework\Plugin\Context\UpdateContext;

class SwagManualMigrationTest extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        $installContext->setAutoMigrate(false);
        $installContext->getMigrationCollection()->migrateInPlace(1);
    }

    public function update(UpdateContext $updateContext): void
    {
        $updateContext->setAutoMigrate(false);
        $updateContext->getMigrationCollection()->migrateDestructiveInPlace(1);
        $updateContext->getMigrationCollection()->migrateInPlace(3);
    }

    public function activate(ActivateContext $activateContext): void
    {
        $activateContext->setAutoMigrate(false);
        $activateContext->getMigrationCollection()->migrateInPlace(2);
    }

    public function deactivate(DeactivateContext $deactivateContext): void
    {
        // nth
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        // nth
    }
}
