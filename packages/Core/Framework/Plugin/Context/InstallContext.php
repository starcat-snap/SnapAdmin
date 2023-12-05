<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Context;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Migration\MigrationCollection;
use SnapAdmin\Core\Framework\Plugin;

#[Package('core')]
class InstallContext
{
    private bool $autoMigrate = true;

    public function __construct(
        private readonly Plugin $plugin,
        private readonly Context $context,
        private readonly string $currentSnapAdminVersion,
        private readonly string $currentPluginVersion,
        private readonly MigrationCollection $migrationCollection
    ) {
    }

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getCurrentSnapAdminVersion(): string
    {
        return $this->currentSnapAdminVersion;
    }

    public function getCurrentPluginVersion(): string
    {
        return $this->currentPluginVersion;
    }

    public function getMigrationCollection(): MigrationCollection
    {
        return $this->migrationCollection;
    }

    public function isAutoMigrate(): bool
    {
        return $this->autoMigrate;
    }

    public function setAutoMigrate(bool $autoMigrate): void
    {
        $this->autoMigrate = $autoMigrate;
    }
}
