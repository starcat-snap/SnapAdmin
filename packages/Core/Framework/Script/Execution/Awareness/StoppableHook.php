<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script\Execution\Awareness;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
interface StoppableHook
{
    public function stopPropagation(): void;

    public function isPropagationStopped(): bool;
}
