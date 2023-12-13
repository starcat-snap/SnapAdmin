<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Aggregate\MediaFolder;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<MediaFolderEntity>
 */
class MediaFolderCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'media_folder_collection';
    }

    protected function getExpectedClass(): string
    {
        return MediaFolderEntity::class;
    }
}
