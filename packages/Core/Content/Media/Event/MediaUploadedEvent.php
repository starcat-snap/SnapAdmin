<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Event;

use SnapAdmin\Core\Content\Flow\Dispatching\Action\FlowMailVariables;
use SnapAdmin\Core\Content\Flow\Dispatching\Aware\ScalarValuesAware;
use SnapAdmin\Core\Framework\App\AppEntity;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\EventData\EventDataCollection;
use SnapAdmin\Core\Framework\Event\EventData\ScalarValueType;
use SnapAdmin\Core\Framework\Event\FlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Webhook\AclPrivilegeCollection;
use SnapAdmin\Core\Framework\Webhook\Hookable;
use Symfony\Contracts\EventDispatcher\Event;


class MediaUploadedEvent extends Event implements ScalarValuesAware, FlowEventAware, Hookable
{
    public const EVENT_NAME = 'media.uploaded';

    public function __construct(
        private readonly string $mediaId,
        private readonly Context $context
    ) {
    }

    public function getName(): string
    {
        return self::EVENT_NAME;
    }

    public static function getAvailableData(): EventDataCollection
    {
        return (new EventDataCollection())
            ->add('mediaId', new ScalarValueType(ScalarValueType::TYPE_STRING));
    }

    public function getValues(): array
    {
        return [
            FlowMailVariables::MEDIA_ID => $this->mediaId,
        ];
    }

    public function getMediaId(): string
    {
        return $this->mediaId;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getWebhookPayload(AppEntity|null $app = null): array
    {
        return [
            'mediaId' => $this->mediaId,
        ];
    }

    public function isAllowed(string $appId, AclPrivilegeCollection $permissions): bool
    {
        return $permissions->isAllowed('media', 'read');
    }
}
