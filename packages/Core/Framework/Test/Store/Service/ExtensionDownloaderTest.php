<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Store\Service;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Plugin\PluginException;
use SnapAdmin\Core\Framework\Store\Exception\CanNotDownloadPluginManagedByComposerException;
use SnapAdmin\Core\Framework\Store\Services\ExtensionDownloader;
use SnapAdmin\Core\Framework\Store\StoreException;
use SnapAdmin\Core\Framework\Test\Store\StoreClientBehaviour;
use SnapAdmin\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @internal
 */
class ExtensionDownloaderTest extends TestCase
{
    use IntegrationTestBehaviour;
    use StoreClientBehaviour;

    /**
     * @var ExtensionDownloader
     */
    private $extensionDownloader;

    public function testDownloadExtensionServerNotReachable(): void
    {
        $this->getRequestHandler()->reset();
        $this->getRequestHandler()->append(new Response(200, [], '{"location": "http://localhost/my.zip"}'));
        $this->getRequestHandler()->append(new Response(500, [], ''));

        $context = $this->createAdminStoreContext();

        static::expectException(PluginException::class);
        static::expectExceptionMessage('Store is not available');
        $this->extensionDownloader->download('TestApp', $context);
    }

    public function testDownloadWhichIsAnComposerExtension(): void
    {
        if (Feature::isActive('v6.6.0.0')) {
            static::expectException(StoreException::class);
        } else {
            static::expectException(CanNotDownloadPluginManagedByComposerException::class);
        }

        $this->getContainer()->get('plugin.repository')->create(
            [
                [
                    'name' => 'TestApp',
                    'label' => 'TestApp',
                    'baseClass' => 'TestApp',
                    'path' => $this->getContainer()->getParameter('kernel.project_dir') . '/vendor/swag/TestApp',
                    'autoload' => [],
                    'version' => '1.0.0',
                    'managedByComposer' => true,
                ],
            ],
            Context::createDefaultContext()
        );

        $this->extensionDownloader->download('TestApp', Context::createDefaultContext(new AdminApiSource(Uuid::randomHex())));
    }
}
