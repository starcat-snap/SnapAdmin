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
class ScheduledTaskGenerator implements ScaffoldingGenerator
{
    use AddScaffoldConfigDefaultBehaviour;
    use HasCommandOption;

    public const OPTION_NAME = 'create-scheduled-task';
    private const OPTION_DESCRIPTION = 'Create an example scheduled task';
    private const CLI_QUESTION = 'Do you want to create an example scheduled task?';

    private string $servicesXmlEntry = <<<'EOL'

            <service id="{{ namespace }}\ScheduledTask\ExampleTask">
                <tag name="snap.scheduled.task"/>
            </service>

    EOL;

    public function generateStubs(
        PluginScaffoldConfiguration $configuration,
        StubCollection $stubCollection
    ): void {
        if (!$configuration->hasOption(self::OPTION_NAME) || !$configuration->getOption(self::OPTION_NAME)) {
            return;
        }

        $stubCollection->add($this->createScheduledTask($configuration));

        $stubCollection->append(
            'src/Resources/config/services.xml',
            str_replace(
                '{{ namespace }}',
                $configuration->namespace,
                $this->servicesXmlEntry
            )
        );
    }

    private function createScheduledTask(PluginScaffoldConfiguration $configuration): Stub
    {
        return Stub::template(
            'src/ScheduledTask/ExampleTask.php',
            self::STUB_DIRECTORY . '/scheduled-task.stub',
            [
                'namespace' => $configuration->namespace,
            ]
        );
    }
}
