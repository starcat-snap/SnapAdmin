<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\TypeDetector;

use SnapAdmin\Core\Content\Media\File\MediaFile;
use SnapAdmin\Core\Content\Media\MediaType\MediaType;
use SnapAdmin\Core\Content\Media\MediaType\VideoType;

class VideoTypeDetector implements TypeDetectorInterface
{
    protected const SUPPORTED_FILE_EXTENSIONS = [
        'webm' => [],
        'mkv' => [],
        'flv' => [],
        'ogv' => [],
        'ogg' => [],
        'avi' => [],
        'mov' => [],
        'wmv' => [],
        'mp4' => [],
    ];

    public function detect(MediaFile $mediaFile, ?MediaType $previouslyDetectedType): ?MediaType
    {
        $fileExtension = mb_strtolower($mediaFile->getFileExtension());
        if (!\array_key_exists($fileExtension, self::SUPPORTED_FILE_EXTENSIONS)) {
            return $previouslyDetectedType;
        }

        if ($previouslyDetectedType === null) {
            $previouslyDetectedType = new VideoType();
        }

        $previouslyDetectedType->addFlags(self::SUPPORTED_FILE_EXTENSIONS[$fileExtension]);

        return $previouslyDetectedType;
    }
}
