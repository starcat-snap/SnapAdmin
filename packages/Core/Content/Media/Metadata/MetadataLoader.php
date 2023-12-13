<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Metadata;

use SnapAdmin\Core\Content\Media\File\MediaFile;
use SnapAdmin\Core\Content\Media\MediaType\MediaType;
use SnapAdmin\Core\Content\Media\Metadata\MetadataLoader\MetadataLoaderInterface;


class MetadataLoader
{
    /**
     * @internal
     *
     * @param MetadataLoaderInterface[] $metadataLoader
     */
    public function __construct(private readonly iterable $metadataLoader)
    {
    }

    public function loadFromFile(MediaFile $mediaFile, MediaType $mediaType): ?array
    {
        foreach ($this->metadataLoader as $loader) {
            if ($loader->supports($mediaType)) {
                $metaData = $loader->extractMetadata($mediaFile->getFileName());

                if ($mediaFile->getHash()) {
                    $metaData['hash'] = $mediaFile->getHash();
                }

                return $metaData;
            }
        }

        return null;
    }
}
