<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Composer;

use Composer\Console\Application;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Exception\PluginComposerRemoveException;
use SnapAdmin\Core\Framework\Plugin\Exception\PluginComposerRequireException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

#[Package('core')]
class CommandExecutor
{
    private readonly Application $application;

    /**
     * @internal
     */
    public function __construct(private readonly string $projectDir)
    {
        $this->application = new Application();
        $this->application->setAutoExit(false);
    }

    public function require(string $pluginComposerName, string $pluginName): void
    {
        $output = new BufferedOutput();
        $input = new ArrayInput(
            [
                'command' => 'require',
                'packages' => [$pluginComposerName],
                '--working-dir' => $this->projectDir,
                '--no-interaction' => null,
                '--update-with-dependencies' => null,
                '--no-scripts' => null,
            ]
        );

        $exitCode = $this->application->run($input, $output);

        if ($exitCode === 0) {
            return;
        }

        throw new PluginComposerRequireException($pluginName, $pluginComposerName, $output->fetch());
    }

    public function remove(string $pluginComposerName, string $pluginName): void
    {
        $output = new BufferedOutput();
        $input = new ArrayInput(
            [
                'command' => 'remove',
                'packages' => [$pluginComposerName],
                '--working-dir' => $this->projectDir,
                '--no-interaction' => null,
                '--no-scripts' => null,
            ]
        );

        $exitCode = $this->application->run($input, $output);

        if ($exitCode === 0) {
            return;
        }

        throw new PluginComposerRemoveException($pluginName, $pluginComposerName, $output->fetch());
    }
}
