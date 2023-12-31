<?php declare(strict_types=1);

namespace SnapAdmin\Core\Maintenance\User\Command;

use SnapAdmin\Core\Framework\Adapter\Console\SnapAdminStyle;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Maintenance\User\Service\UserProvisioner;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @internal should be used over the CLI only
 */
#[AsCommand(
    name: 'user:create',
    description: 'Creates a new user',
)]
#[Package('core')]
class UserCreateCommand extends Command
{
    public function __construct(
        private readonly UserProvisioner $userProvisioner,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username for the user')
            ->addOption('admin', 'a', InputOption::VALUE_NONE, 'mark the user as admin')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'Password for the user')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'The user\'s name')
            ->addOption('phone', null, InputOption::VALUE_REQUIRED, 'The user\'s phone')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'E-Mail for the user');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SnapAdminStyle($input, $output);

        $username = $input->getArgument('username');
        $password = $input->getOption('password');

        if (!$password) {
            $passwordQuestion = new Question('Enter password for user');
            $passwordQuestion->setValidator(static function ($value): string {
                if ($value === null || trim($value) === '') {
                    throw new \RuntimeException('The password cannot be empty');
                }

                return $value;
            });
            $passwordQuestion->setHidden(true);
            $passwordQuestion->setMaxAttempts(3);

            $password = $io->askQuestion($passwordQuestion);
        }

        $additionalData = [];
        if ($input->getOption('name')) {
            $additionalData['name'] = $input->getOption('name');
        }
        if ($input->getOption('phone')) {
            $additionalData['phone'] = $input->getOption('phone');
        }
        if ($input->getOption('email')) {
            $additionalData['email'] = $input->getOption('email');
        }
        if ($input->getOption('admin')) {
            $additionalData['admin'] = true;
        }

        $this->userProvisioner->provision($username, $password, $additionalData);

        $io->success(sprintf('User "%s" successfully created.', $username));

        return self::SUCCESS;
    }
}
