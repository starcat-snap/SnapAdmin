<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\TypeDetector;

use SnapAdmin\Core\Content\Media\File\MediaFile;
use SnapAdmin\Core\Content\Media\MediaType\MediaType;

interface TypeDetectorInterface
{
    public function detect(MediaFile $mediaFile, ?MediaType $previouslyDetectedType): ?MediaType;
}
