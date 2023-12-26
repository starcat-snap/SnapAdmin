<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script\Execution;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
interface DeprecatedHook
{
    public static function getDeprecationNotice(): string;
}
