<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Event;

use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnail\MediaThumbnailCollection;
use SnapAdmin\Core\Framework\Context;
use Symfony\Contracts\EventDispatcher\Event;


class MediaThumbnailDeletedEvent extends Event
{
    final public const EVENT_NAME = 'media_thumbnail.after_delete';

    public function __construct(
        private readonly MediaThumbnailCollection $thumbnails,
        private readonly Context $context
    ) {
    }

    public function getThumbnails(): MediaThumbnailCollection
    {
        return $this->thumbnails;
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}
