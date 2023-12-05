<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Asset;

use Composer\Console\Input\InputOption;
use SnapAdmin\Core\Framework\Adapter\Console\SnapAdminStyle;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Util\AssetService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'assets:install',
    description: 'Installs bundles web assets under a public web directory',
)]
#[Package('core')]
class AssetInstallCommand extends Command
{
    /**
     * @internal
     */
    public function __construct(
        private readonly KernelInterface  $kernel,
        private readonly AssetService     $assetService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'Force the install of assets regardless of the manifest state');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SnapAdminStyle($input, $output);

        foreach ($this->kernel->getBundles() as $bundle) {
            $io->writeln(sprintf('Copying files for bundle: %s', $bundle->getName()));
            $this->assetService->copyAssetsFromBundle($bundle->getName(), $input->getOption('force'));
        }

        $publicDir = $this->kernel->getProjectDir() . '/public/';

        if (!file_exists($publicDir . '/.htaccess') && file_exists($publicDir . '/.htaccess.dist')) {
            $io->writeln('Copying .htaccess.dist to .htaccess');
            copy($publicDir . '/.htaccess.dist', $publicDir . '/.htaccess');
        }

        $io->success('Successfully copied all bundle files');

        return self::SUCCESS;
    }
}
