<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Command\Scaffolding\Generator;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Command\Scaffolding\PluginScaffoldConfiguration;
use SnapAdmin\Core\Framework\Plugin\Command\Scaffolding\Stub;
use SnapAdmin\Core\Framework\Plugin\Command\Scaffolding\StubCollection;

/**
 * @internal
 */
#[Package('core')]
class JavascriptPluginGenerator implements ScaffoldingGenerator
{
    use AddScaffoldConfigDefaultBehaviour;
    use HasCommandOption;

    public const OPTION_NAME = 'create-javascript-plugin';
    private const OPTION_DESCRIPTION = 'Create an example javascript plugin';
    private const CLI_QUESTION = 'Do you want to create an example javascript plugin?';

    private string $mainJsEntry = <<<'EOL'
    // Import all necessary Frontend plugins
    import ExamplePlugin from './example-plugin/example-plugin.plugin';

    // Register your plugin via the existing PluginManager
    const PluginManager = window.PluginManager;

    PluginManager.register('ExamplePlugin', ExamplePlugin, '[data-example-plugin]');

    EOL;

    public function generateStubs(
        PluginScaffoldConfiguration $configuration,
        StubCollection              $stubCollection
    ): void
    {
        if (!$configuration->hasOption(self::OPTION_NAME) || !$configuration->getOption(self::OPTION_NAME)) {
            return;
        }

        $stubCollection->add($this->createPluginFile());
        $stubCollection->add($this->createTemplate());

        $stubCollection->append(
            'src/Resources/app/frontend/src/main.js',
            $this->mainJsEntry
        );
    }

    private function createPluginFile(): Stub
    {
        return Stub::template(
            'src/Resources/app/frontend/src/example-plugin/example-plugin.plugin.js',
            self::STUB_DIRECTORY . '/js-plugin.stub'
        );
    }

    private function createTemplate(): Stub
    {
        return Stub::template(
            'src/Resources/views/frontend/page/content/index.html.twig',
            self::STUB_DIRECTORY . '/js-plugin-template.stub'
        );
    }
}
