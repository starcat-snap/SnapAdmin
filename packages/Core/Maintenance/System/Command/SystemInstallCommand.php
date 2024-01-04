<?php declare(strict_types=1);

namespace SnapAdmin\Core\Maintenance\System\Command;

use SnapAdmin\Core\DevOps\Environment\EnvironmentHelper;
use SnapAdmin\Core\Framework\Adapter\Console\SnapAdminStyle;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Maintenance\System\Service\DatabaseConnectionFactory;
use SnapAdmin\Core\Maintenance\System\Service\SetupDatabaseAdapter;
use SnapAdmin\Core\Maintenance\System\Struct\DatabaseConnectionInformation;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal should be used over the CLI only
 */
#[AsCommand(
    name: 'system:install',
    description: 'Installs the SnapAdmin 6 system',
)]
#[Package('core')]
class SystemInstallCommand extends Command
{
    public function __construct(
        private readonly string $projectDir,
        private readonly SetupDatabaseAdapter $setupDatabaseAdapter,
        private readonly DatabaseConnectionFactory $databaseConnectionFactory
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('create-database', null, InputOption::VALUE_NONE, 'Create database if it doesn\'t exist.')
            ->addOption('drop-database', null, InputOption::VALUE_NONE, 'Drop existing database')
            ->addOption('basic-setup', null, InputOption::VALUE_NONE, 'Create admin user')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force install even if install.lock exists')
            ->addOption('skip-jwt-keys-generation', null, InputOption::VALUE_NONE, 'Skips generation of jwt private and public key')
            ->addOption('skip-assets-install', null, InputOption::VALUE_NONE, 'Skips installing of assets');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output = new SnapAdminStyle($input, $output);

        // set default
        $isBlueGreen = EnvironmentHelper::getVariable('BLUE_GREEN_DEPLOYMENT', '1');
        $_ENV['BLUE_GREEN_DEPLOYMENT'] = $_SERVER['BLUE_GREEN_DEPLOYMENT'] = $isBlueGreen;
        putenv('BLUE_GREEN_DEPLOYMENT=' . $isBlueGreen);

        if (!$input->getOption('force') && file_exists($this->projectDir . '/install.lock')) {
            $output->comment('install.lock already exists. Delete it or pass --force to do it anyway.');

            return self::FAILURE;
        }

        $this->initializeDatabase($output, $input);

        $commands = [
            [
                'command' => 'database:migrate',
                'identifier' => 'core',
                '--all' => true,
            ],
            [
                'command' => 'database:migrate-destructive',
                'identifier' => 'core',
                '--all' => true,
                '--version-selection-mode' => 'all',
            ],
            [
                'command' => 'dal:refresh:index',
            ],
            [
                'command' => 'scheduled-task:register',
            ],
            [
                'command' => 'plugin:refresh',
            ],
        ];

        if (!$input->getOption('skip-jwt-keys-generation')) {
            array_unshift(
                $commands,
                [
                    'command' => 'system:generate-jwt',
                    'allowedToFail' => true,
                ]
            );
        }

        /** @var Application $application */
        $application = $this->getApplication();

        if ($input->getOption('basic-setup')) {
            $commands[] = [
                'command' => 'user:create',
                'username' => 'admin',
                '--admin' => true,
                '--password' => 'snapadmin',
            ];
        }

        if (!$input->getOption('skip-assets-install')) {
            $commands[] = [
                'command' => 'assets:install',
            ];
        }

        $commands[] = [
            'command' => 'cache:clear',
        ];

        $this->runCommands($commands, $output);

        if (!file_exists($this->projectDir . '/public/.htaccess')
            && file_exists($this->projectDir . '/public/.htaccess.dist')
        ) {
            copy($this->projectDir . '/public/.htaccess.dist', $this->projectDir . '/public/.htaccess');
        }

        touch($this->projectDir . '/install.lock');

        return self::SUCCESS;
    }

    /**
     * @param array<int, array<string, string|bool|null>> $commands
     */
    private function runCommands(array $commands, OutputInterface $output): int
    {
        $application = $this->getApplication();

        \assert($application !== null);

        return $this->runCommandByApplication($application, $commands, $output);
    }

    /**
     * @param array<int, array<string, string|bool|null>> $commands
     */
    private function runCommandByApplication(Application $application, array $commands, OutputInterface $output): int
    {
        foreach ($commands as $parameters) {
            // remove params with null value
            $parameters = array_filter($parameters);

            $output->writeln('');

            $allowedToFail = $parameters['allowedToFail'] ?? false;
            unset($parameters['allowedToFail']);

            try {
                $returnCode = $application->doRun(new ArrayInput($parameters), $output);

                if ($returnCode !== 0 && !$allowedToFail) {
                    return $returnCode;
                }
            } catch (\Throwable $e) {
                if (!$allowedToFail) {
                    throw $e;
                }
            }
        }

        return self::SUCCESS;
    }

    private function initializeDatabase(SnapAdminStyle $output, InputInterface $input): void
    {
        $databaseConnectionInformation = DatabaseConnectionInformation::fromEnv();

        $connection = $this->databaseConnectionFactory->getConnection($databaseConnectionInformation, true);

        $output->writeln('Prepare installation');
        $output->writeln('');

        $dropDatabase = $input->getOption('drop-database');
        if ($dropDatabase) {
            $this->setupDatabaseAdapter->dropDatabase($connection, $databaseConnectionInformation->getDatabaseName());
            $output->writeln('Drop database `' . $databaseConnectionInformation->getDatabaseName() . '`');
        }

        $createDatabase = $input->getOption('create-database') || $dropDatabase;
        if ($createDatabase) {
            $this->setupDatabaseAdapter->createDatabase($connection, $databaseConnectionInformation->getDatabaseName());
            $output->writeln('Created database `' . $databaseConnectionInformation->getDatabaseName() . '`');
        }

        $importedBaseSchema = $this->setupDatabaseAdapter->initializeSnapAdminDb($connection, $databaseConnectionInformation->getDatabaseName());

        if ($importedBaseSchema) {
            $output->writeln('Imported base schema.sql');
        }

        $output->writeln('');
    }
}
