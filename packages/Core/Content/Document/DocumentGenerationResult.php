<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document;

use SnapAdmin\Core\Framework\Api\EventListener\ErrorResponseFactory;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @final
 */
#[Package('content')]
class DocumentGenerationResult extends Struct
{
    private readonly DocumentIdCollection $success;

    /**
     * @var \Throwable[]
     */
    private array $errors = [];

    public function __construct()
    {
        $this->success = new DocumentIdCollection();
    }

    public function getSuccess(): DocumentIdCollection
    {
        return $this->success;
    }

    public function addSuccess(DocumentIdStruct $document): void
    {
        $this->success->add($document);
    }

    /**
     * @return \Throwable[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $orderId, \Throwable $exception): void
    {
        $this->errors[$orderId] = $exception;
    }

    public function jsonSerialize(): array
    {
        $errors = [];

        foreach ($this->errors as $orderId => $error) {
            $errors[$orderId] = (new ErrorResponseFactory())->getErrorsFromException($error);
        }

        return [
            'data' => $this->success->map(fn (DocumentIdStruct $documentIdStruct) => [
                'documentId' => $documentIdStruct->getId(),
                'documentMediaId' => $documentIdStruct->getMediaId(),
                'documentDeepLink' => $documentIdStruct->getDeepLinkCode(),
            ]),
            'errors' => $errors,
        ];
    }
}
