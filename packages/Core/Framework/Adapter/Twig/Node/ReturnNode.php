<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\Node;

use SnapAdmin\Core\Framework\Log\Package;
use Twig\Compiler;
use Twig\Node\Node;
use Twig\Node\NodeOutputInterface;

#[Package('core')]
class ReturnNode extends Node implements NodeOutputInterface
{
    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this)
            ->write('return ');

        if ($this->hasNode('expr')) {
            $compiler->subcompile($this->getNode('expr'));
            $compiler->raw(";\n");

            return;
        }

        $compiler->raw(";\n");
    }
}
