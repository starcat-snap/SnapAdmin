<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnailSize;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<MediaThumbnailSizeEntity>
 */
class MediaThumbnailSizeCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'media_thumbnail_size_collection';
    }

    protected function getExpectedClass(): string
    {
        return MediaThumbnailSizeEntity::class;
    }
}
