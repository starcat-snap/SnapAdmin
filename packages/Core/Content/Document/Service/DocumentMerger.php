<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Service;

use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;
use SnapAdmin\Core\Content\Document\DocumentCollection;
use SnapAdmin\Core\Content\Document\DocumentConfigurationFactory;
use SnapAdmin\Core\Content\Document\DocumentEntity;
use SnapAdmin\Core\Content\Document\FileGenerator\FileTypes;
use SnapAdmin\Core\Content\Document\Renderer\RenderedDocument;
use SnapAdmin\Core\Content\Document\Struct\DocumentGenerateOperation;
use SnapAdmin\Core\Content\Media\MediaService;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Util\Random;

#[Package('content')]
final class DocumentMerger
{
    /**
     * @internal
     *
     * @param EntityRepository<DocumentCollection> $documentRepository
     */
    public function __construct(
        private readonly EntityRepository $documentRepository,
        private readonly MediaService $mediaService,
        private readonly DocumentGenerator $documentGenerator,
        private readonly Fpdi $fpdi
    ) {
    }

    /**
     * @param array<string> $documentIds
     */
    public function merge(array $documentIds, Context $context): ?RenderedDocument
    {
        if (empty($documentIds)) {
            return null;
        }

        $this->fpdi->setPrintHeader(false);
        $this->fpdi->setPrintFooter(false);

        $criteria = new Criteria($documentIds);
        $criteria->addAssociation('documentType');
        $criteria->addSorting(new FieldSorting('order.orderNumber'));

        $documents = $this->documentRepository->search($criteria, $context)->getEntities();

        if ($documents->count() === 0) {
            return null;
        }

        $fileName = Random::getAlphanumericString(32) . '.' . PdfRenderer::FILE_EXTENSION;

        if ($documents->count() === 1) {
            $document = $documents->first();
            if ($document === null) {
                return null;
            }

            $documentMediaId = $this->ensureDocumentMediaFileGenerated($document, $context);

            if ($documentMediaId === null) {
                return null;
            }

            $fileBlob = $context->scope(Context::SYSTEM_SCOPE, fn (Context $context): string => $this->mediaService->loadFile($documentMediaId, $context));

            $renderedDocument = new RenderedDocument('', '', $fileName);
            $renderedDocument->setContent($fileBlob);

            return $renderedDocument;
        }

        $totalPage = 0;
        foreach ($documents as $document) {
            $documentMediaId = $this->ensureDocumentMediaFileGenerated($document, $context);

            if ($documentMediaId === null) {
                continue;
            }

            $config = DocumentConfigurationFactory::createConfiguration($document->getConfig());

            $media = $context->scope(Context::SYSTEM_SCOPE, fn (Context $context): string => $this->mediaService->loadFileStream($documentMediaId, $context)->getContents());

            $numPages = $this->fpdi->setSourceFile(StreamReader::createByString($media));

            $totalPage += $numPages;
            for ($i = 1; $i <= $numPages; ++$i) {
                $template = $this->fpdi->importPage($i);
                $size = $this->fpdi->getTemplateSize($template);
                if (!\is_array($size)) {
                    continue;
                }
                $this->fpdi->AddPage($config->getPageOrientation() ?? 'portrait', $config->getPageSize());
                $this->fpdi->useTemplate($template);
            }
        }

        if ($totalPage === 0) {
            return null;
        }

        $renderedDocument = new RenderedDocument('', '', $fileName);

        $renderedDocument->setContent($this->fpdi->Output($fileName, 'S'));
        $renderedDocument->setContentType(PdfRenderer::FILE_CONTENT_TYPE);
        $renderedDocument->setName($fileName);

        return $renderedDocument;
    }

    private function ensureDocumentMediaFileGenerated(DocumentEntity $document, Context $context): ?string
    {
        $documentMediaId = $document->getDocumentMediaFileId();

        if ($documentMediaId !== null || $document->isStatic()) {
            return $documentMediaId;
        }

        $operation = new DocumentGenerateOperation(
            $document->getOrderId(),
            FileTypes::PDF,
            $document->getConfig(),
            $document->getReferencedDocumentId()
        );

        $operation->setDocumentId($document->getId());

        $documentType = $document->getDocumentType();
        if ($documentType === null) {
            return null;
        }

        $documentStruct = $this->documentGenerator->generate(
            $documentType->getTechnicalName(),
            [$document->getOrderId() => $operation],
            $context
        )->getSuccess()->first();

        if ($documentStruct === null) {
            return null;
        }

        $documentMediaId = $documentStruct->getMediaId();
        $document->setDocumentMediaFileId($documentMediaId);

        return $documentMediaId;
    }
}
