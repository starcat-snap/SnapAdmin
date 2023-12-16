<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Snippet;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Kernel;
use Symfony\Component\Finder\Finder;

#[Package('administration')]
class SnippetFinder implements SnippetFinderInterface
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Kernel $kernel
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function findSnippets(string $locale): array
    {
        $snippetFiles = $this->findSnippetFiles($locale);
        $snippets = $this->parseFiles($snippetFiles);

        $snippets = [...$snippets];

        if (!\count($snippets)) {
            return [];
        }

        return $snippets;
    }

    /**
     * @return array<int, string>
     */
    private function getPluginPaths(): array
    {
        $plugins = $this->kernel->getPluginLoader()->getPluginInstances()->all();
        $activePlugins = $this->kernel->getPluginLoader()->getPluginInstances()->getActives();
        $bundles = $this->kernel->getBundles();
        $paths = [];

        foreach ($activePlugins as $plugin) {
            $pluginPath = $plugin->getPath() . '/Resources/app/administration';
            if (!file_exists($pluginPath)) {
                continue;
            }

            $paths[] = $pluginPath;
        }

        foreach ($bundles as $bundle) {
            if (\in_array($bundle, $plugins, true)) {
                continue;
            }

            $bundlePath = $bundle->getPath() . '/Resources/app/administration';

            if (!file_exists($bundlePath)) {
                continue;
            }

            $paths[] = $bundlePath;
        }

        return $paths;
    }

    /**
     * @return array<int, string>
     */
    private function findSnippetFiles(?string $locale = null, bool $withPlugins = true): array
    {
        $finder = (new Finder())
            ->files()
            ->in(__DIR__ . '/../../*/Resources/app/administration/src/')
            ->exclude('node_modules')
            ->ignoreUnreadableDirs();

        if ($locale) {
            $finder->name(sprintf('%s.json', $locale));
        } else {
            $finder->name('/[a-z]{2}-[A-Z]{2}\.json/');
        }

        if ($withPlugins) {
            $finder->in($this->getPluginPaths());
        }

        $iterator = $finder->getIterator();
        $files = [];

        foreach ($iterator as $file) {
            $files[] = $file->getRealPath();
        }

        return \array_unique($files);
    }

    /**
     * @param array<int, string> $files
     *
     * @return array<string, mixed>
     */
    private function parseFiles(array $files): array
    {
        $snippets = [[]];

        foreach ($files as $file) {
            if (is_file($file) === false) {
                continue;
            }

            $content = file_get_contents($file);
            if ($content !== false) {
                $snippets[] = json_decode($content, true, 512, \JSON_THROW_ON_ERROR) ?? [];
            }
        }

        $snippets = array_replace_recursive(...$snippets);

        ksort($snippets);

        return $snippets;
    }
}
