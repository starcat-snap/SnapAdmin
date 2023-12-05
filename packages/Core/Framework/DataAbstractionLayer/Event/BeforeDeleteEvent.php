<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Event;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @deprecated tag:v6.6.0 - Use `\SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityDeleteEvent` instead
 */
#[Package('core')]
class BeforeDeleteEvent extends EntityDeleteEvent
{
}
