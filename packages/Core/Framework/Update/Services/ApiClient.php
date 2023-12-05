<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Update\Services;

use SnapAdmin\Core\DevOps\Environment\EnvironmentHelper;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Update\Struct\Version;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @phpstan-import-type VersionFixedVulnerabilities from Version
 */

/**
 * @phpstan-import-type VersionFixedVulnerabilities from Version
 */
#[Package('system-settings')]
class ApiClient
{
    /**
     * @internal
     */
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly bool                $snapUpdateEnabled,
        private readonly string              $snapVersion,
        private readonly string              $projectDir
    )
    {
    }

    public function checkForUpdates(): Version
    {
        $fakeVersion = EnvironmentHelper::getVariable('SW_RECOVERY_NEXT_VERSION');
        if (\is_string($fakeVersion)) {
            return new Version([
                'version' => $fakeVersion,
                'title' => 'SnapAdmin ' . $fakeVersion,
                'body' => 'This is a fake version for testing purposes',
                'date' => new \DateTimeImmutable(),
                'fixedVulnerabilities' => [],
            ]);
        }

        if (!$this->snapUpdateEnabled) {
            return new Version();
        }

        try {
            /** @var array{title: string, body: string, date: string, version: string, fixedVulnerabilities: VersionFixedVulnerabilities[]} $github */
            $github = $this->client->request('GET', 'https://releases.snap.com/changelog/' . $this->determineLatestSnapAdminVersion() . '.json')->toArray();
        } catch (ClientException $e) {
            if ($e->getCode() === Response::HTTP_NOT_FOUND || $e->getCode() === Response::HTTP_FORBIDDEN) {
                return new Version();
            }

            throw $e;
        }

        $version = new Version();
        $version->title = $github['title'];
        $version->body = $github['body'];
        $version->date = new \DateTimeImmutable($github['date']);
        $version->version = $github['version'];
        $version->fixedVulnerabilities = $github['fixedVulnerabilities'];

        return $version;
    }

    public function downloadRecoveryTool(): void
    {
        if (\is_string(EnvironmentHelper::getVariable('SW_RECOVERY_NEXT_VERSION'))) {
            return;
        }

        $content = $this->client->request('GET', 'https://github.com/snap/web-installer/releases/latest/download/snap-installer.phar.php')->getContent();

        file_put_contents($this->projectDir . '/public/snap-installer.phar.php', $content);
    }

    private function determineLatestSnapAdminVersion(): string
    {
        /** @var non-empty-array<string> $versions */
        $versions = $this->client->request('GET', 'https://releases.snap.com/changelog/index.json')->toArray();

        usort($versions, function ($a, $b) {
            return version_compare($b, $a);
        });

        // Index them by major version
        $mappedVersions = [];

        foreach ($versions as $version) {
            if (str_contains($version, 'rc')) {
                continue;
            }

            $major = substr($version, 0, 3);

            if (isset($mappedVersions[$major])) {
                continue;
            }

            $mappedVersions[$major] = $version;
        }

        $currentMajor = substr($this->snapVersion, 0, 3);
        if (!isset($mappedVersions[$currentMajor])) {
            return strtolower($this->snapVersion);
        }

        $latestVersion = $mappedVersions[$currentMajor];

        $first = (int)substr($this->snapVersion, 0, 1);
        $second = (int)substr($this->snapVersion, 2, 1);
        ++$second;

        if (isset($mappedVersions[$first . '.' . $second])) {
            $latestVersion = $mappedVersions[$first . '.' . $second];
        }

        return $latestVersion;
    }
}
