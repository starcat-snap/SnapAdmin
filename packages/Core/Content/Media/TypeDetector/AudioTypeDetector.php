<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\TypeDetector;

use SnapAdmin\Core\Content\Media\File\MediaFile;
use SnapAdmin\Core\Content\Media\MediaType\AudioType;
use SnapAdmin\Core\Content\Media\MediaType\MediaType;

class AudioTypeDetector implements TypeDetectorInterface
{
    protected const SUPPORTED_FILE_EXTENSIONS = [
        'aac' => [],
        'flac' => [],
        'mp3' => [],
        'oga' => [],
        'wav' => [],
        'wma' => [],
    ];

    public function detect(MediaFile $mediaFile, ?MediaType $previouslyDetectedType): ?MediaType
    {
        $fileExtension = mb_strtolower($mediaFile->getFileExtension());
        if (!\array_key_exists($fileExtension, self::SUPPORTED_FILE_EXTENSIONS)) {
            return $previouslyDetectedType;
        }

        if ($previouslyDetectedType === null) {
            $previouslyDetectedType = new AudioType();
        }

        $previouslyDetectedType->addFlags(self::SUPPORTED_FILE_EXTENSIONS[$fileExtension]);

        return $previouslyDetectedType;
    }
}
