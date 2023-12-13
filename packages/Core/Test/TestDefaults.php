<?php declare(strict_types=1);

namespace SnapAdmin\Core\Test;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 * This class contains some defaults for test case
 */
#[Package('core')]
class TestDefaults
{
    final public const HASHED_PASSWORD = '$2y$10$iSfQ0ccQy9xTgJ/helAR1euuJCukaqTxyOecq4sd1OiXxItdGeM9K';
}
