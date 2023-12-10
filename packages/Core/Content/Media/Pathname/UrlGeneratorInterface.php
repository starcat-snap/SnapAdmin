<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Pathname;

use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnail\MediaThumbnailEntity;
use SnapAdmin\Core\Content\Media\MediaEntity;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @deprecated tag:v6.6.0 - Use AbstractMediaUrlGenerator instead
 */

interface UrlGeneratorInterface
{
    public function getAbsoluteMediaUrl(MediaEntity $media): string;

    public function getRelativeMediaUrl(MediaEntity $media): string;

    public function getAbsoluteThumbnailUrl(MediaEntity $media, MediaThumbnailEntity $thumbnail): string;

    public function getRelativeThumbnailUrl(MediaEntity $media, MediaThumbnailEntity $thumbnail): string;
}
