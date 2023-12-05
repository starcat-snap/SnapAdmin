<?php declare(strict_types=1);

namespace SnapAdmin\Core\Maintenance\System\Command;

use Defuse\Crypto\Key;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal should be used over the CLI only
 */
#[AsCommand(
    name: 'system:generate-app-secret',
    description: 'Generates a new app secret',
)]
#[Package('core')]
class SystemGenerateAppSecretCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = Key::createNewRandomKey();

        $output->writeln($key->saveToAsciiSafeString());

        return self::SUCCESS;
    }
}
