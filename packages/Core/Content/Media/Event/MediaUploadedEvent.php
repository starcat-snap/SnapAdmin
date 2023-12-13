<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\EventData\EventDataCollection;
use SnapAdmin\Core\Framework\Event\EventData\ScalarValueType;
use Symfony\Contracts\EventDispatcher\Event;

class MediaUploadedEvent extends Event
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
            'mediaId' => $this->mediaId,
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
}
