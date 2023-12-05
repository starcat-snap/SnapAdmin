<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Log;

use Monolog\Level;

#[Package('core')]
interface LogAware
{
    /**
     * @return array<string, mixed>
     */
    public function getLogData(): array;

    public function getLogLevel(): Level;
}
