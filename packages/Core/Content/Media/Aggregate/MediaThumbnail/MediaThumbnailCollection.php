<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnail;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<MediaThumbnailEntity>
 */
class MediaThumbnailCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'media_thumbnail_collection';
    }

    protected function getExpectedClass(): string
    {
        return MediaThumbnailEntity::class;
    }
}
