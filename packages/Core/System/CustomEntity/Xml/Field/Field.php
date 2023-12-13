<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity\Xml\Field;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\XmlElement;
use Symfony\Component\Config\Util\XmlUtils;

/**
 * @internal
 */
#[Package('core')]
abstract class Field extends XmlElement
{
    protected string $name;

    protected bool $storeApiAware;

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();
        unset($data['extensions']);

        return $data;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isStoreApiAware(): bool
    {
        return $this->storeApiAware;
    }

    protected static function parse(\DOMElement $element): array
    {
        $values = [];

        foreach ($element->attributes as $attribute) {
            if (!$attribute instanceof \DOMAttr) {
                continue;
            }
            $name = self::kebabCaseToCamelCase($attribute->name);

            $values[$name] = XmlUtils::phpize($attribute->value);
        }

        foreach ($element->childNodes as $child) {
            if (!$child instanceof \DOMElement || $child->nodeValue === null) {
                continue;
            }

            $values[self::kebabCaseToCamelCase($child->tagName)] = XmlUtils::phpize($child->nodeValue);
        }

        return $values;
    }
}
