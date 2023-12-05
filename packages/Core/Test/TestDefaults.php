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
    final public const HASHED_PASSWORD = '$2y$10$XFRhv2TdOz9GItRt6ZgHl.e/HpO5Mfea6zDNXI9Q8BasBRtWbqSTS';
}
