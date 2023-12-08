<?php declare(strict_types=1);

namespace SnapAdmin\Core\DevOps\StaticAnalyze;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Kernel;

/**
 * @internal
 */
#[Package('core')]
class StaticAnalyzeKernel extends Kernel
{
    public function getCacheDir(): string
    {
        return sprintf(
            '%s/var/cache/%s',
            $this->getProjectDir(),
            $this->getEnvironment()
        );
    }
}
