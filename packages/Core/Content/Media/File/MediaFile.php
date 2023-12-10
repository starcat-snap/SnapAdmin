<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\File;

use SnapAdmin\Core\Framework\Log\Package;


class MediaFile
{
    public function __construct(
        private readonly string $fileName,
        private readonly string $mimeType,
        private readonly string $fileExtension,
        private readonly int $fileSize,
        private readonly ?string $hash = null
    ) {
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }
}
