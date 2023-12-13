<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\NamespaceHierarchy;

use SnapAdmin\Core\Framework\Bundle;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpKernel\KernelInterface;

#[Package('core')]
class BundleHierarchyBuilder implements TemplateNamespaceHierarchyBuilderInterface
{
    /**
     * @internal
     */
    public function __construct(
        private readonly KernelInterface $kernel
    )
    {
    }

    public function buildNamespaceHierarchy(array $namespaceHierarchy): array
    {
        $bundles = [];

        foreach ($this->kernel->getBundles() as $bundle) {
            if (!$bundle instanceof Bundle) {
                continue;
            }

            $bundlePath = $bundle->getPath();

            $directory = $bundlePath . '/Resources/views';

            if (!file_exists($directory)) {
                continue;
            }

            $bundles[$bundle->getName()] = $bundle->getTemplatePriority();
        }

        $bundles = array_reverse($bundles);

        $extensions = array_merge($bundles);
        asort($extensions);

        return array_merge(
            $extensions,
            $namespaceHierarchy
        );
    }
}
