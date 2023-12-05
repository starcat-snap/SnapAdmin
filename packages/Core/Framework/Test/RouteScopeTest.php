<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test;

use SnapAdmin\Core\Checkout\Payment\Controller\PaymentController;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Test\TestCaseBase\KernelTestBehaviourTest;
use SnapAdmin\Core\PlatformRequest;
use Symfony\Component\Routing\Router;

/**
 * @internal
 */
#[Package('core')]
class RouteScopeTest extends KernelTestBehaviourTest
{
    public function testAllRoutesHaveRouteScopes(): void
    {
        /** @var Router $router */
        $router = $this->getKernel()->getContainer()->get('router');

        $routeCollection = $router->getRouteCollection();

        $errors = [];
        $errorMessage = 'No RouteScope defined for following Methods';

        foreach ($routeCollection as $route) {
            if (!$controllerMethod = $route->getDefault('_controller')) {
                continue;
            }

            $controllerMethod = explode('::', (string) $controllerMethod);

            // The payment controller must work also without scopes due headless
            if ($controllerMethod[0] === PaymentController::class) {
                continue;
            }

            if ($route->getDefault(PlatformRequest::ATTRIBUTE_ROUTE_SCOPE) === null) {
                $errors[] = $route->getDefault('_controller');
            }
        }

        $errorMessage .= "\n" . print_r($errors, true);

        static::assertCount(0, $errors, $errorMessage);
    }
}
