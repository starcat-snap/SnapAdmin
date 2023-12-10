<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Pathname\PathnameStrategy;

use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnail\MediaThumbnailEntity;
use SnapAdmin\Core\Content\Media\MediaEntity;
use SnapAdmin\Core\Framework\Log\Package;


class IdPathnameStrategy extends AbstractPathNameStrategy
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'id';
    }

    /**
     * {@inheritdoc}
     */
    public function generatePathHash(MediaEntity $media, ?MediaThumbnailEntity $thumbnail = null): ?string
    {
        return $this->generateMd5Path($media->getId());
    }
}
