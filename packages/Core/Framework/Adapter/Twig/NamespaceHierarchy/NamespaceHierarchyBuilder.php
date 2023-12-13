<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\NamespaceHierarchy;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class NamespaceHierarchyBuilder
{
    /**
     * @param TemplateNamespaceHierarchyBuilderInterface[] $namespaceHierarchyBuilders
     *
     * @internal
     */
    public function __construct(private readonly iterable $namespaceHierarchyBuilders)
    {
    }

    public function buildHierarchy(): array
    {
        $hierarchy = [];

        foreach ($this->namespaceHierarchyBuilders as $hierarchyBuilder) {
            $hierarchy = $hierarchyBuilder->buildNamespaceHierarchy($hierarchy);
        }

        return $hierarchy;
    }
}
