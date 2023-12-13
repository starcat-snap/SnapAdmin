<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Subscriber;

use SnapAdmin\Core\Content\Media\Aggregate\MediaFolderConfiguration\MediaFolderConfigurationEntity;
use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnailSize\MediaThumbnailSizeCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @internal
 */

class MediaFolderConfigLoadedSubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'media_folder_configuration.loaded' => [
                ['unserialize', 10],
            ],
        ];
    }

    public function unserialize(EntityLoadedEvent $event): void
    {
        /** @var MediaFolderConfigurationEntity $media */
        foreach ($event->getEntities() as $media) {
            if ($media->getMediaThumbnailSizes() === null) {
                if ($media->getMediaThumbnailSizesRo()) {
                    $media->setMediaThumbnailSizes(unserialize($media->getMediaThumbnailSizesRo()));
                } else {
                    $media->setMediaThumbnailSizes(new MediaThumbnailSizeCollection());
                }
            }
        }
    }
}
