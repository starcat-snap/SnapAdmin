<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Event\EventData\EventDataCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('business-ops')]
interface FlowEventAware extends SnapAdminEvent
{
    public static function getAvailableData(): EventDataCollection;

    public function getName(): string;
}
