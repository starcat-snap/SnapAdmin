<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Pathname\PathnameStrategy;

use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnail\MediaThumbnailEntity;
use SnapAdmin\Core\Content\Media\MediaEntity;
use SnapAdmin\Core\Framework\Log\Package;


class PlainPathnameStrategy extends AbstractPathNameStrategy
{
    /**
     * {@inheritdoc}
     */
    public function generatePathHash(MediaEntity $media, ?MediaThumbnailEntity $thumbnail = null): ?string
    {
        return null;
    }

    /**
     * Name of the strategy
     */
    public function getName(): string
    {
        return 'plain';
    }
}
