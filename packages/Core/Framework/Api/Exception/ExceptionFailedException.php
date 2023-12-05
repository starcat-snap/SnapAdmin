<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Exception;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @deprecated tag:v6.6.0 - Will be removed, use SnapAdmin\Core\Framework\Api\Exception\ExpectationFailedException instead
 */
#[Package('core')]
class ExceptionFailedException extends ExpectationFailedException
{
}
