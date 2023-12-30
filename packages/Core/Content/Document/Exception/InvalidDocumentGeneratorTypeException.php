<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Exception;

use SnapAdmin\Core\Content\Document\DocumentException;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('content')]
class InvalidDocumentGeneratorTypeException extends DocumentException
{
}
