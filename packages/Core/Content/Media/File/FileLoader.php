<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\File;

use League\Flysystem\FilesystemOperator;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use SnapAdmin\Core\Content\Media\MediaEntity;
use SnapAdmin\Core\Content\Media\MediaException;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;

class FileLoader
{
    private readonly FileNameValidator $fileNameValidator;

    /**
     * @internal
     */
    public function __construct(
        private readonly FilesystemOperator $filesystemPublic,
        private readonly FilesystemOperator $filesystemPrivate,
        private readonly EntityRepository $mediaRepository,
        private readonly StreamFactoryInterface $streamFactory
    ) {
        $this->fileNameValidator = new FileNameValidator();
    }

    public function loadMediaFile(string $mediaId, Context $context): string
    {
        $media = $this->findMediaById($mediaId, $context);

        return $this->getFileSystem($media)->read($this->getFilePath($media)) ?: '';
    }

    public function loadMediaFileStream(string $mediaId, Context $context): StreamInterface
    {
        $media = $this->findMediaById($mediaId, $context);
        $resource = $this->getFileSystem($media)->readStream($this->getFilePath($media));

        return $this->streamFactory->createStreamFromResource($resource);
    }

    private function getFilePath(MediaEntity $media): string
    {
        $this->fileNameValidator->validateFileName($media->getFileName() ?: '');

        return $media->getPath();
    }

    private function getFileSystem(MediaEntity $media): FilesystemOperator
    {
        if ($media->isPrivate()) {
            return $this->filesystemPrivate;
        }

        return $this->filesystemPublic;
    }

    /**
     * @throws MediaException
     */
    private function findMediaById(string $mediaId, Context $context): MediaEntity
    {
        $criteria = new Criteria([$mediaId]);
        $criteria->addAssociation('mediaFolder');

        $currentMedia = $this->mediaRepository
            ->search($criteria, $context)
            ->get($mediaId);

        if (!$currentMedia instanceof MediaEntity) {
            throw MediaException::mediaNotFound($mediaId);
        }

        return $currentMedia;
    }
}
