<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Command;

use Composer\IO\ConsoleIO;
use SnapAdmin\Core\Framework\Adapter\Console\SnapAdminStyle;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\PluginService;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'plugin:refresh',
    description: 'Refreshes the plugin list',
)]
#[Package('core')]
class PluginRefreshCommand extends Command
{
    /**
     * @internal
     */
    public function __construct(private readonly PluginService $pluginService)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->addOption('skipPluginList', 's', InputOption::VALUE_NONE, 'Don\'t display plugin list after refresh');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SnapAdminStyle($input, $output);
        $io->title('SnapAdmin Plugin Service');
        $context = Context::createDefaultContext();

        $composerInput = clone $input;
        $composerInput->setInteractive(false);
        $helperSet = $this->getHelperSet();
        \assert($helperSet instanceof HelperSet);
        $errors = $this->pluginService->refreshPlugins($context, new ConsoleIO($composerInput, $output, $helperSet));
        $io->success('Plugin list refreshed');

        if (\count($errors) !== 0) {
            $io->writeln('Errors occurred while refreshing plugin list');
            foreach ($errors as $key => $error) {
                if (\is_int($key)) {
                    $io->error($error->getMessage());
                } else {
                    $io->error($key . ': ' . $error->getMessage());
                }
            }
        }

        $skipPluginList = $input->getOption('skipPluginList');
        if ($skipPluginList) {
            return self::SUCCESS;
        }

        $listInput = new StringInput('plugin:list');

        /** @var Application $application */
        $application = $this->getApplication();
        $application->doRun($listInput, $output);

        return self::SUCCESS;
    }
}
