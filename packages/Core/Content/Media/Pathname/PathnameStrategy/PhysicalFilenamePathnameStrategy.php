<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Pathname\PathnameStrategy;

use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnail\MediaThumbnailEntity;
use SnapAdmin\Core\Content\Media\MediaEntity;
use SnapAdmin\Core\Framework\Log\Package;


class PhysicalFilenamePathnameStrategy extends AbstractPathNameStrategy
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'physical_filename';
    }

    /**
     * {@inheritdoc}
     */
    public function generatePathHash(MediaEntity $media, ?MediaThumbnailEntity $thumbnail = null): ?string
    {
        $timestamp = $media->getUploadedAt() ? $media->getUploadedAt()->getTimestamp() . '/' : '';

        return $this->generateMd5Path($timestamp . $media->getFileName());
    }
}
