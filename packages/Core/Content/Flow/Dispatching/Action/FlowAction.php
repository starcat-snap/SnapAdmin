<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching\Action;

use SnapAdmin\Core\Content\Flow\Dispatching\StorableFlow;
use SnapAdmin\Core\Framework\Log\Package;

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
