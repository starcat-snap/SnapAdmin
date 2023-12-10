<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\File;

use SnapAdmin\Core\Content\Media\MediaCollection;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use SnapAdmin\Core\Framework\Log\Package;


abstract class FileNameProvider
{
    /**
     * @internal
     */
    public function __construct(private readonly EntityRepository $mediaRepository)
    {
    }

    public function provide(
        string $preferredFileName,
        string $fileExtension,
        ?string $mediaId,
        Context $context
    ): string {
        $mediaWithRelatedFilename = $this->finderOtherMediaWithFileName(
            $preferredFileName,
            $fileExtension,
            $mediaId,
            $context
        );

        return $this->getPossibleFileName($mediaWithRelatedFilename, $preferredFileName);
    }

    abstract protected function getNextFileName(
        string $originalFileName,
        MediaCollection $relatedMedia,
        int $iteration
    ): string;

    private function finderOtherMediaWithFileName(
        string $fileName,
        string $fileExtension,
        ?string $mediaId,
        Context $context
    ): MediaCollection {
        $criteria = new Criteria();
        $criteria->addFilter(new MultiFilter(
            MultiFilter::CONNECTION_AND,
            [
                new ContainsFilter('fileName', $fileName),
                new EqualsFilter('fileExtension', $fileExtension),
                new NotFilter(NotFilter::CONNECTION_AND, [new EqualsFilter('id', $mediaId)]),
            ]
        ));

        $search = $this->mediaRepository->search($criteria, $context);

        /** @var MediaCollection $mediaCollection */
        $mediaCollection = $search->getEntities();

        return $mediaCollection;
    }

    private function getPossibleFileName(
        MediaCollection $relatedMedia,
        string $preferredFileName,
        int $iteration = 0
    ): string {
        $nextFileName = $this->getNextFileName($preferredFileName, $relatedMedia, $iteration);

        foreach ($relatedMedia as $media) {
            if ($media->hasFile() && $media->getFileName() === $nextFileName) {
                return $this->getPossibleFileName($relatedMedia, $preferredFileName, $iteration + 1);
            }
        }

        return $nextFileName;
    }
}
