<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Update\Checkers;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Update\Services\Filesystem;
use SnapAdmin\Core\Framework\Update\Struct\ValidationResult;

#[Package('system-settings')]
class WriteableCheck
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly string     $rootDir
    )
    {
    }

    public function check(): ValidationResult
    {
        $directories = [];
        $checkedDirectories = [];

        $fullPath = rtrim($this->rootDir . '/');
        $checkedDirectories[] = $fullPath;

        $directories = array_merge(
            $directories,
            $this->filesystem->checkSingleDirectoryPermissions($fullPath, true)
        );

        if (empty($directories)) {
            return new ValidationResult(
                'writeableCheck',
                true,
                'writeableCheckValid',
                ['checkedDirectories' => implode('<br>', $checkedDirectories)]
            );
        }

        return new ValidationResult(
            'writeableCheck',
            false,
            'writeableCheckFailed',
            ['failedDirectories' => implode('<br>', $directories)]
        );
    }
}
