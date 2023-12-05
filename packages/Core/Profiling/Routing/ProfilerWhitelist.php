<?php declare(strict_types=1);

namespace SnapAdmin\Core\Profiling\Routing;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Routing\RouteScopeWhitelistInterface;
use SnapAdmin\Core\Profiling\Controller\ProfilerController;

#[Package('core')]
class ProfilerWhitelist implements RouteScopeWhitelistInterface
{
    public function applies(string $controllerClass): bool
    {
        return $controllerClass === ProfilerController::class;
    }
}
