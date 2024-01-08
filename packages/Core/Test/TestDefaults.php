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
    final public const SCOPE_ID = '98432def39fc4624b33213a56b8c944d';

    final public const HASHED_PASSWORD = '$2y$10$ynRKQc5B4nGW.5eNYedEfev2ubgyQM6.Nga09T2gK1P9jWPNheDKa';
}
