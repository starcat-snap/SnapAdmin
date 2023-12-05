<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @codeCoverageIgnore
 */
#[Package('services-settings')]
abstract class StoreStruct extends Struct
{
    /**
     * @param array<string, mixed> $data
     */
    abstract public static function fromArray(array $data): self;
}
