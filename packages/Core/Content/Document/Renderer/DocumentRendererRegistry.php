<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Renderer;

use SnapAdmin\Core\Content\Document\DocumentException;
use SnapAdmin\Core\Content\Document\Struct\DocumentGenerateOperation;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('content')]
final class DocumentRendererRegistry
{
    /**
     * @internal
     *
     * @param AbstractDocumentRenderer[] $documentRenderers
     */
    public function __construct(protected iterable $documentRenderers)
    {
    }

    /**
     * @param DocumentGenerateOperation[] $operations
     */
    public function render(string $documentType, array $operations, Context $context, DocumentRendererConfig $rendererConfig): RendererResult
    {
        foreach ($this->documentRenderers as $documentRenderer) {
            if ($documentRenderer->supports() !== $documentType) {
                continue;
            }

            return $documentRenderer->render($operations, $context, $rendererConfig);
        }

        throw DocumentException::invalidDocumentGeneratorType($documentType);
    }
}
