<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Util\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Util\UtilException;
use Symfony\Component\HttpFoundation\Response;

#[Package('system-settings')]
class UtilXmlParsingException extends UtilException
{
    public function __construct(
        string $xmlFile,
        string $message
    )
    {
        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            self::XML_PARSE_ERROR,
            'Unable to parse file "{{ file }}". Message: {{ message }}',
            ['file' => $xmlFile, 'message' => $message]
        );
    }
}
