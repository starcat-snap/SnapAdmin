<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('services-settings')]
class GenerateDocumentActionException extends SnapAdminHttpException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public function getErrorCode(): string
    {
        return 'FLOW_BUILDER__DOCUMENT_GENERATION_ERROR';
    }
}
