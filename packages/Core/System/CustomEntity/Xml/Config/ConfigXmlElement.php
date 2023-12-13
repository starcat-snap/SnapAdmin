<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity\Xml\Config;

use SnapAdmin\Core\Framework\Struct\XmlElement;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('content')]
abstract class ConfigXmlElement extends XmlElement
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();
        unset($data['extensions']);

        return $data;
    }
}
