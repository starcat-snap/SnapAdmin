<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\NamespaceHierarchy;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
interface TemplateNamespaceHierarchyBuilderInterface
{
    /**
     * Gets the current hierarchy as param and can extend and modify the hierarchy.
     * Needs to return the new hierarchy.
     * Example hierarchy structure:
     * [
     *     'Frontend',
     *     'SwagPayPal',
     *     'MyOwnTheme',
     * ]
     *
     * @param array<string> $namespaceHierarchy
     *
     * @return array<string>
     */
    public function buildNamespaceHierarchy(array $namespaceHierarchy): array;
}
