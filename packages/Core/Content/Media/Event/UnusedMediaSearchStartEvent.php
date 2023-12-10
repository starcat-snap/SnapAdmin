<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Event;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */

class UnusedMediaSearchStartEvent
{
    public function __construct(public int $totalMedia, public int $totalMediaDeletionCandidates)
    {
    }
}
