<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document;

use SnapAdmin\Core\Content\Document\Exception\InvalidDocumentGeneratorTypeException;
use SnapAdmin\Core\Framework\HttpException;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

#[Package('content')]
class DocumentException extends HttpException
{
    public const INVALID_DOCUMENT_GENERATOR_TYPE_CODE = 'DOCUMENT__INVALID_GENERATOR_TYPE';

    public const ORDER_NOT_FOUND = 'DOCUMENT__ORDER_NOT_FOUND';

    public const DOCUMENT_NOT_FOUND = 'DOCUMENT__DOCUMENT_NOT_FOUND';

    public const GENERATION_ERROR = 'DOCUMENT__GENERATION_ERROR';

    public static function invalidDocumentGeneratorType(string $type): self
    {
        return new InvalidDocumentGeneratorTypeException(
            Response::HTTP_BAD_REQUEST,
            DocumentException::INVALID_DOCUMENT_GENERATOR_TYPE_CODE,
            'Unable to find a document generator with type "{{ type }}"',
            ['type' => $type]
        );
    }

    public static function orderNotFound(string $orderId, ?\Throwable $e = null): self
    {
        return new self(
            Response::HTTP_NOT_FOUND,
            self::ORDER_NOT_FOUND,
            'The order with id {{ orderId }} is invalid or could not be found.',
            [
                'orderId' => $orderId,
            ],
            $e
        );
    }

    public static function documentNotFound(string $documentId, ?\Throwable $e = null): self
    {
        return new self(
            Response::HTTP_NOT_FOUND,
            self::DOCUMENT_NOT_FOUND,
            'The document with id "{{ documentId }}" is invalid or could not be found.',
            [
                'documentId' => $documentId,
            ],
            $e
        );
    }

    public static function generationError(?string $message = null, ?\Throwable $e = null): self
    {
        return new self(
            Response::HTTP_NOT_FOUND,
            self::GENERATION_ERROR,
            sprintf('Unable to generate document. %s', $message),
            [
                '$message' => $message,
            ],
            $e
        );
    }
}
