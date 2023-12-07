<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Routing;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Administration\Controller\AdministrationController;
use SnapAdmin\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour;
use SnapAdmin\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
class CoreSubscriberTest extends TestCase
{
    use AdminApiTestBehaviour;
    use IntegrationTestBehaviour;

    public function testDefaultHeadersHttp(): void
    {
        $browser = $this->getBrowser();

        $browser->request('GET', '/api/category');
        $response = $browser->getResponse();

        static::assertTrue($response->headers->has('X-Frame-Options'));
        static::assertTrue($response->headers->has('X-Content-Type-Options'));
        static::assertTrue($response->headers->has('Content-Security-Policy'));

        static::assertFalse($response->headers->has('Strict-Transport-Security'));
    }

    public function testDefaultHeadersHttps(): void
    {
        $browser = $this->getBrowser();
        $browser->setServerParameter('HTTPS', true);

        $browser->request('GET', '/api/category');
        $response = $browser->getResponse();

        static::assertTrue($response->headers->has('X-Frame-Options'));
        static::assertTrue($response->headers->has('X-Content-Type-Options'));
        static::assertTrue($response->headers->has('Content-Security-Policy'));

        static::assertTrue($response->headers->has('Strict-Transport-Security'));
    }


    public function testAdminHasCsp(): void
    {
        if (!$this->getContainer()->has(AdministrationController::class)) {
            static::markTestSkipped('Admin CSP test need admin bundle to be installed');
        }

        $browser = $this->getBrowser();
        $browser->request('GET', $_SERVER['APP_URL'] . '/admin');
        $response = $browser->getResponse();

        static::assertTrue($response->headers->has('X-Frame-Options'));
        static::assertTrue($response->headers->has('X-Content-Type-Options'));
        static::assertTrue($response->headers->has('Content-Security-Policy'));

        $nonce = $this->getNonceFromCsp($response);

        static::assertMatchesRegularExpression(
            '/.*script-src[^;]+nonce-' . preg_quote($nonce, '/') . '.*/',
            (string)$response->headers->get('Content-Security-Policy'),
            'CSP should contain the nonce'
        );
        static::assertStringNotContainsString("\n", (string)$response->headers->get('Content-Security-Policy'));
        static::assertStringNotContainsString("\r", (string)$response->headers->get('Content-Security-Policy'));
    }

    public function testSwaggerHasCsp(): void
    {
        $browser = $this->getBrowser();

        $browser->request('GET', '/api/_info/swagger.html');
        $response = $browser->getResponse();

        static::assertTrue($response->headers->has('X-Frame-Options'));
        static::assertTrue($response->headers->has('X-Content-Type-Options'));
        static::assertTrue($response->headers->has('Content-Security-Policy'));

        $nonce = $this->getNonceFromCsp($response);

        static::assertMatchesRegularExpression(
            '/.*script-src[^;]+nonce-' . preg_quote($nonce, '/') . '.*/',
            (string)$response->headers->get('Content-Security-Policy'),
            'CSP should contain the nonce'
        );
    }

    public function testOptionsRequestWorks(): void
    {
        $browser = $this->getBrowser();

        $browser->request('OPTIONS', '/api/_info/swagger.html');
        $response = $browser->getResponse();

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertFalse($response->headers->has('Content-Security-Policy'));
    }

    public function getNonceFromCsp(Response $response): string
    {
        $csp = (string)$response->headers->get('Content-Security-Policy');
        preg_match('/nonce-([\w+-=]*)/m', $csp, $matches);

        static::assertArrayHasKey(1, $matches);

        return $matches[1];
    }
}
