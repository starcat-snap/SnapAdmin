<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\CustomEntity\CustomEntityException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class CustomEntityXmlParsingException extends CustomEntityException
{
    public function __construct(
        string $xmlFile,
        string $message
    ) {
        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            self::XML_PARSE_ERROR,
            'Unable to parse file "{{ file }}". Message: {{ message }}',
            ['file' => $xmlFile, 'message' => $message]
        );
    }
}
