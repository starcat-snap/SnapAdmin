<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching\Aware;

use SnapAdmin\Core\Framework\Event\IsFlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
#[IsFlowEventAware]
interface ScalarValuesAware
{
    public const STORE_VALUES = 'store_values';

    /**
     * @return array<string, scalar|array<mixed>|null>
     */
    public function getValues(): array;
}
