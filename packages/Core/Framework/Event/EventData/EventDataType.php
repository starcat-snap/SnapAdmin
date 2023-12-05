<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event\EventData;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('business-ops')]
interface EventDataType
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
