<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class NoDeprecationFoundException extends \Exception
{
}
