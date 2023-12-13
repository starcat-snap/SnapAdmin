<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Commands;

use SnapAdmin\Core\Content\Media\File\MediaFile;
use SnapAdmin\Core\Content\Media\MediaCollection;
use SnapAdmin\Core\Content\Media\MediaEntity;
use SnapAdmin\Core\Content\Media\MediaException;
use SnapAdmin\Core\Content\Media\TypeDetector\TypeDetector;
use SnapAdmin\Core\Framework\Adapter\Console\SnapAdminStyle;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'media:generate-media-types',
    description: 'Generates media types for all media files',
)]
class GenerateMediaTypesCommand extends Command
{
    private SnapAdminStyle $io;

    private ?int $batchSize = null;

    /**
     * @internal
     */
    public function __construct(
        private readonly TypeDetector $typeDetector,
        private readonly EntityRepository $mediaRepository
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->addOption('batch-size', 'b', InputOption::VALUE_REQUIRED, 'Batch Size')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SnapAdminStyle($input, $output);

        $context = Context::createDefaultContext();
        $this->batchSize = $this->validateBatchSize($input);

        $this->io->comment('Starting to generate MediaTypes. This may take some time...');
        $this->io->progressStart($this->getMediaCount($context));

        $this->detectMediaTypes($context);

        $this->io->progressFinish();

        return self::SUCCESS;
    }

    private function validateBatchSize(InputInterface $input): int
    {
        $batchSize = $input->getOption('batch-size');
        if ($batchSize === null) {
            return 100;
        }

        if (!is_numeric($batchSize)) {
            throw MediaException::invalidBatchSize();
        }

        return (int) $batchSize;
    }

    private function getMediaCount(Context $context): int
    {
        $criteria = new Criteria();
        $criteria->setTotalCountMode(Criteria::TOTAL_COUNT_MODE_EXACT);
        $criteria->setLimit(1);
        $result = $this->mediaRepository->search($criteria, $context);

        return $result->getTotal();
    }

    private function detectMediaTypes(Context $context): void
    {
        $criteria = $this->createCriteria();

        do {
            $result = $this->mediaRepository->search($criteria, $context);

            /** @var MediaCollection $medias */
            $medias = $result->getEntities();
            foreach ($medias as $media) {
                $this->detectMediaType($context, $media);
            }
            $this->io->progressAdvance($result->count());
            $criteria->setOffset($criteria->getOffset() + $this->batchSize);
        } while ($result->getTotal() > $this->batchSize);
    }

    private function detectMediaType(Context $context, MediaEntity $media): void
    {
        if (!$media->hasFile()) {
            return;
        }

        $file = new MediaFile(
            $media->getUrl(),
            $media->getMimeType() ?? '',
            $media->getFileExtension() ?? '',
            $media->getFileSize() ?? 0
        );

        $type = $this->typeDetector->detect($file);
        $changeSet = ['id' => $media->getId(), 'mediaTypeRaw' => serialize($type)];

        $context->scope(Context::SYSTEM_SCOPE, function (Context $context) use ($changeSet): void {
            $this->mediaRepository->upsert([$changeSet], $context);
        });
    }

    private function createCriteria(): Criteria
    {
        $criteria = new Criteria();
        $criteria->setTotalCountMode(Criteria::TOTAL_COUNT_MODE_NEXT_PAGES);
        $criteria->setLimit($this->batchSize);

        return $criteria;
    }
}
