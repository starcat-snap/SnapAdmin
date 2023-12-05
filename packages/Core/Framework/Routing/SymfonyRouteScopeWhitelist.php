<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class SymfonyRouteScopeWhitelist implements RouteScopeWhitelistInterface
{
    /**
     * {@inheritdoc}
     */
    public function applies(string $controllerClass): bool
    {
        return str_starts_with($controllerClass, 'Symfony\\');
    }
}
