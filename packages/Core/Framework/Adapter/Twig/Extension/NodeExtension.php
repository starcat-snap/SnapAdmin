<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\Extension;

use SnapAdmin\Core\Framework\Adapter\Twig\TemplateFinder;
use SnapAdmin\Core\Framework\Adapter\Twig\TokenParser\IncludeTokenParser;
use SnapAdmin\Core\Framework\Adapter\Twig\TokenParser\ReturnNodeTokenParser;
use SnapAdmin\Core\Framework\Log\Package;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;

#[Package('core')]
class NodeExtension extends AbstractExtension
{
    /**
     * @internal
     */
    public function __construct(
        private readonly TemplateFinder $finder
    ) {
    }

    /**
     * @return TokenParserInterface[]
     */
    public function getTokenParsers(): array
    {
        return [
            new IncludeTokenParser($this->finder),
            new ReturnNodeTokenParser(),
        ];
    }

    public function getFinder(): TemplateFinder
    {
        return $this->finder;
    }
}
