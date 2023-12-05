<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Exception;

use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Util\UtilException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated tag:v6.7.0 - will be removed, use UtilException::xmlElementNotFound instead
 */
#[Package('system-settings')]
class XmlElementNotFoundException extends UtilException
{
    public function __construct(string $element)
    {
        Feature::triggerDeprecationOrThrow(
            'v6.7.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.7.0.0', 'AppException::xmlParsingException')
        );

        parent::__construct(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            self::XML_ELEMENT_NOT_FOUND,
            'Unable to locate element with the name "{{ element }}".',
            ['element' => $element]
        );
    }
}
