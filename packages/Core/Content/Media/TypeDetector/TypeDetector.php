<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\TypeDetector;

use SnapAdmin\Core\Content\Media\File\MediaFile;
use SnapAdmin\Core\Content\Media\MediaType\MediaType;

class TypeDetector implements TypeDetectorInterface
{
    /**
     * @internal
     *
     * @param TypeDetectorInterface[] $typeDetector
     */
    public function __construct(private readonly iterable $typeDetector)
    {
    }

    public function detect(MediaFile $mediaFile, ?MediaType $previouslyDetectedType = null): MediaType
    {
        $mediaType = null;
        foreach ($this->typeDetector as $typeDetector) {
            $mediaType = $typeDetector->detect($mediaFile, $mediaType);
        }

        return $mediaType;
    }
}
