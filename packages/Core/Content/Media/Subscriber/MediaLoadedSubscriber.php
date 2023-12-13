<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Subscriber;

use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnail\MediaThumbnailCollection;
use SnapAdmin\Core\Content\Media\MediaEntity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;


class MediaLoadedSubscriber
{
    public function unserialize(EntityLoadedEvent $event): void
    {
        /** @var MediaEntity $media */
        foreach ($event->getEntities() as $media) {
            if ($media->getMediaTypeRaw()) {
                $media->setMediaType(unserialize($media->getMediaTypeRaw()));
            }

            if ($media->getThumbnails() !== null) {
                continue;
            }

            $thumbnails = match (true) {
                $media->getThumbnailsRo() !== null => unserialize($media->getThumbnailsRo()),
                default => new MediaThumbnailCollection(),
            };

            $media->setThumbnails($thumbnails);
        }
    }
}
