<?php declare(strict_types=1);

namespace SnapAdmin\Core\Maintenance\System\Command;

use SnapAdmin\Core\DevOps\Environment\EnvironmentHelper;
use SnapAdmin\Core\Framework\Adapter\Console\SnapAdminStyle;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\PluginLifecycleService;
use SnapAdmin\Core\Framework\Update\Api\UpdateController;
use SnapAdmin\Core\Framework\Update\Event\UpdatePostFinishEvent;
use SnapAdmin\Core\Framework\Update\Event\UpdatePreFinishEvent;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @internal should be used over the CLI only
 */
#[AsCommand(
    name: 'system:update:finish',
    description: 'Finishes the update process',
)]
#[Package('core')]
class SystemUpdateFinishCommand extends Command
{
    public function __construct(private readonly ContainerInterface $container, private readonly string $snapVersion)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'skip-asset-build',
                null,
                InputOption::VALUE_NONE,
                'Use this option to skip asset building'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output = new SnapAdminStyle($input, $output);

        $dsn = trim((string) EnvironmentHelper::getVariable('DATABASE_URL', getenv('DATABASE_URL')));
        if ($dsn === '') {
            $output->note('Environment variable \'DATABASE_URL\' not defined. Skipping ' . $this->getName() . '...');

            return self::SUCCESS;
        }

        $output->writeln('Run Post Update');
        $output->writeln('');

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->container->get('event_dispatcher');

        $context = Context::createDefaultContext();
        $systemConfigService = $this->container->get(SystemConfigService::class);
        $oldVersion = $systemConfigService->getString(UpdateController::UPDATE_PREVIOUS_VERSION_KEY);

        if ($input->getOption('skip-asset-build')) {
            $context->addState(PluginLifecycleService::STATE_SKIP_ASSET_BUILDING);
        }

        $eventDispatcher->dispatch(new UpdatePreFinishEvent($context, $oldVersion, $this->snapVersion));

        $this->runMigrations($output);

        $updateEvent = new UpdatePostFinishEvent($context, $oldVersion, $this->snapVersion);
        $eventDispatcher->dispatch($updateEvent);
        $output->writeln($updateEvent->getPostUpdateMessage());

        $this->installAssets($output);

        $output->writeln('');

        return self::SUCCESS;
    }

    private function runMigrations(OutputInterface $output): int
    {
        $application = $this->getApplication();
        \assert($application !== null);
        $command = $application->find('database:migrate');

        return $this->runCommand($application, $command, [
            'identifier' => 'core',
            '--all' => true,
        ], $output);
    }

    private function installAssets(OutputInterface $output): int
    {
        $application = $this->getApplication();
        \assert($application !== null);
        $command = $application->find('assets:install');

        return $this->runCommand($application, $command, [], $output);
    }

    /**
     * @param array<string, string|bool|null> $arguments
     */
    private function runCommand(Application $application, Command $command, array $arguments, OutputInterface $output): int
    {
        \array_unshift($arguments, $command->getName());

        return $application->doRun(
            new ArrayInput($arguments),
            $output
        );
    }
}
