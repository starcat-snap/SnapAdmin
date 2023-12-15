<?php declare(strict_types=1);

namespace SnapAdmin\Tests\Integration\Core\Framework\Api\Controller;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Test\TestCaseBase\AdminFunctionalTestBehaviour;
use SnapAdmin\Core\Kernel;

/**
 * @internal
 */
class InfoControllerTest extends TestCase
{
    use AdminFunctionalTestBehaviour;

    public function testGetConfig(): void
    {
        $expected = [
            'version' => Kernel::SNAP_FALLBACK_VERSION,
            'versionRevision' => str_repeat('0', 32),
            'adminWorker' => [
                'enableAdminWorker' => $this->getContainer()->getParameter('snap.admin_worker.enable_admin_worker'),
                'enableQueueStatsWorker' => $this->getContainer()->getParameter('snap.admin_worker.enable_queue_stats_worker'),
                'enableNotificationWorker' => $this->getContainer()->getParameter('snap.admin_worker.enable_notification_worker'),
                'transports' => $this->getContainer()->getParameter('snap.admin_worker.transports'),
            ],
            'bundles' => [],
            'settings' => [
                'enableUrlFeature' => true,
                'appUrlReachable' => true,
                'private_allowed_extensions' => $this->getContainer()->getParameter('snap.filesystem.private_allowed_extensions'),
                'enableHtmlSanitizer' => $this->getContainer()->getParameter('snap.html_sanitizer.enabled'),
            ],
        ];

        $url = '/api/_info/config';
        $client = $this->getBrowser();
        $client->request('GET', $url);

        $content = $client->getResponse()->getContent();
        static::assertNotFalse($content);
        static::assertJson($content);

        $decodedResponse = json_decode($content, true, 512, \JSON_THROW_ON_ERROR);

        static::assertSame(200, $client->getResponse()->getStatusCode());

        // reset environment based miss match
        $decodedResponse['bundles'] = [];
        $decodedResponse['versionRevision'] = $expected['versionRevision'];

        static::assertEquals($decodedResponse, $expected);
    }
}
