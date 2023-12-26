<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching\Aware;

use SnapAdmin\Core\Framework\Event\IsFlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
#[IsFlowEventAware]
interface CustomAppAware
{
    public const CUSTOM_DATA = 'customAppData';

    /**
     * @return array<string, mixed>|null
     */
    public function getCustomAppData(): ?array;
}
