<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing;

use Storefront\Checkout\Payment\Controller\PaymentController;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class PaymentScopeWhitelist implements RouteScopeWhitelistInterface
{
    public function applies(string $controllerClass): bool
    {
        return $controllerClass === PaymentController::class;
    }
}
