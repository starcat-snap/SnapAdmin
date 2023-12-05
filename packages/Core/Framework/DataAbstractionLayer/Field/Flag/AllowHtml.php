<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * In case a column is allowed to contain HTML-esque data. Beware of injection possibilities
 */
#[Package('core')]
class AllowHtml extends Flag
{
    /**
     * @var bool
     */
    protected $sanitized;

    public function __construct(bool $sanitized = true)
    {
        $this->sanitized = $sanitized;
    }

    public function parse(): \Generator
    {
        yield 'allow_html' => true;
    }

    public function isSanitized(): bool
    {
        return $this->sanitized;
    }
}
