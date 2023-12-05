<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\Node;

use SnapAdmin\Core\Framework\Adapter\Twig\Extension\NodeExtension;
use SnapAdmin\Core\Framework\Log\Package;
use Twig\Compiler;
use Twig\Node\IncludeNode;

#[Package('core')]
class SwInclude extends IncludeNode
{
    protected function addGetTemplate(Compiler $compiler): void
    {
        $compiler
            ->write("((function () use (\$context, \$blocks) {\n")
            ->indent()
                ->write('$finder = $this->env->getExtension(\'' . NodeExtension::class . '\')->getFinder();')->raw("\n\n")
                ->write('$includeTemplate = $finder->find(')
                        ->subcompile($this->getNode('expr'))
                ->raw(");\n\n")
                ->write('return $this->loadTemplate(')
                    ->raw('$includeTemplate ?? null, ')
                    ->repr($this->getTemplateName())->raw(', ')
                    ->repr($this->getTemplateLine())
                ->raw(");\n")
            ->outdent()
            ->write('})())');
    }
}
