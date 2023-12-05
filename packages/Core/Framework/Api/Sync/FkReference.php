<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Sync;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class FkReference
{
    public ?string $resolved = null;

    public function __construct(public mixed $value)
    {
    }
}
