<?php declare(strict_types=1);

namespace SnapAdmin\Core\Installer\Finish;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class SystemLocker
{
    public function __construct(private readonly string $projectDir)
    {
    }

    public function lock(): void
    {
        file_put_contents($this->projectDir . '/install.lock', date('YmdHi'));
    }
}
