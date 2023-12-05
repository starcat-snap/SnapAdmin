<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Webhook\EventLog;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BlobField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\JsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class WebhookEventLogDefinition extends EntityDefinition
{
    final public const STATUS_QUEUED = 'queued';

    final public const STATUS_RUNNING = 'running';

    final public const STATUS_FAILED = 'failed';

    final public const STATUS_SUCCESS = 'success';

    final public const ENTITY_NAME = 'webhook_event_log';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return WebhookEventLogEntity::class;
    }

    public function getCollectionClass(): string
    {
        return WebhookEventLogCollection::class;
    }

    public function getDefaults(): array
    {
        return [
            'onlyLiveVersion' => false,
        ];
    }

    public function since(): ?string
    {
        return '6.4.1.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new StringField('app_name', 'appName'),
            (new StringField('webhook_name', 'webhookName'))->addFlags(new Required()),
            (new StringField('event_name', 'eventName'))->addFlags(new Required()),
            (new StringField('delivery_status', 'deliveryStatus'))->addFlags(new Required()),
            new IntField('timestamp', 'timestamp'),
            new IntField('processing_time', 'processingTime'),
            new StringField('app_version', 'appVersion'),
            new JsonField('request_content', 'requestContent'),
            new JsonField('response_content', 'responseContent'),
            new IntField('response_status_code', 'responseStatusCode'),
            new StringField('response_reason_phrase', 'responseReasonPhrase'),
            (new StringField('url', 'url', 500))->addFlags(new Required()),
            new BoolField('only_live_version', 'onlyLiveVersion'),
            (new BlobField('serialized_webhook_message', 'serializedWebhookMessage'))->removeFlag(ApiAware::class)->addFlags(new Required(), new WriteProtected(Context::SYSTEM_SCOPE)),
            new CustomFields(),
        ]);
    }
}
