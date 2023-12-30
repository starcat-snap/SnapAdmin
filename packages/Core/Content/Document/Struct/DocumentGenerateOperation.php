<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Struct;

use SnapAdmin\Core\Content\Document\FileGenerator\FileTypes;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

#[Package('content')]
final class DocumentGenerateOperation extends Struct
{
    protected ?string $documentId = null;

    protected string $orderVersionId = Defaults::LIVE_VERSION;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        protected string $fileType = FileTypes::PDF,
        protected array $config = [],
        protected ?string $referencedDocumentId = null,
        protected bool $static = false,
        protected bool $preview = false
    ) {
    }
    public function getFileType(): string
    {
        return $this->fileType;
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function isStatic(): bool
    {
        return $this->static;
    }

    public function setReferencedDocumentId(string $referencedDocumentId): void
    {
        $this->referencedDocumentId = $referencedDocumentId;
    }

    public function getReferencedDocumentId(): ?string
    {
        return $this->referencedDocumentId;
    }

    public function isPreview(): bool
    {
        return $this->preview;
    }

    public function getDocumentId(): ?string
    {
        return $this->documentId;
    }

    public function setDocumentId(string $documentId): void
    {
        $this->documentId = $documentId;
    }

    public function getOrderVersionId(): string
    {
        return $this->orderVersionId;
    }

    public function setOrderVersionId(string $orderVersionId): void
    {
        $this->orderVersionId = $orderVersionId;
    }
}
