<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Dispatching\Action;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Flow\Dispatching\StorableFlow;

#[Package('services-settings')]
abstract class FlowAction
{
    /**
     * @return array<int, string>
     */
    abstract public function requirements(): array;

    abstract public function handleFlow(StorableFlow $flow): void;

    abstract public static function getName(): string;
}
