<?php declare(strict_types=1);

namespace SnapAdmin\Core\Installer\Finish;

use GuzzleHttp\Client;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class Notifier
{
    final public const EVENT_INSTALL_STARTED = 'Installer started';
    final public const EVENT_INSTALL_FINISHED = 'Installer finished';

    public function __construct(
        private readonly string $apiEndPoint,
        private readonly UniqueIdGenerator $idGenerator,
        private readonly Client $client,
        private readonly string $SnapAdminVersion
    ) {
    }

    /**
     * @param array<string, string> $additionalInformation
     */
    public function doTrackEvent(string $eventName, array $additionalInformation = []): void
    {
        $additionalInformation['SnapAdminVersion'] = $this->SnapAdminVersion;
        $payload = [
            'additionalData' => $additionalInformation,
            'instanceId' => $this->idGenerator->getUniqueId(),
            'event' => $eventName,
        ];

        try {
            $this->client->postAsync($this->apiEndPoint . '/swplatform/tracking/events', ['json' => $payload]);
        } catch (\Exception) {
            // ignore
        }
    }
}
