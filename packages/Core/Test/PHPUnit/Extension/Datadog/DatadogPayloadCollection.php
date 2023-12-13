<?php declare(strict_types=1);

namespace SnapAdmin\Core\Test\PHPUnit\Extension\Datadog;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @internal
 *
 * @extends Collection<DatadogPayload>
 */
#[Package('core')]
class DatadogPayloadCollection extends Collection
{
}
