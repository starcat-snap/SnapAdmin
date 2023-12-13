<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\Node;

use SnapAdmin\Core\Framework\Log\Package;
use Twig\Compiler;
use Twig\Node\Node;

#[Package('core')]
class FeatureCallSilentToken extends Node
{
    public function __construct(
        private readonly string $flag,
        Node $body,
        int $line,
        string $tag
    ) {
        parent::__construct(['body' => $body], [], $line, $tag);
    }

    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this)
            ->raw('\SnapAdmin\Core\Framework\Feature::callSilentIfInactive(\'' . $this->flag . '\', function () use(&$context) { ')
            ->subcompile($this->getNode('body'))
            ->raw('});');
    }
}
