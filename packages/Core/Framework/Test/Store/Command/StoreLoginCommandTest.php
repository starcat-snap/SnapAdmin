<?php

declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Store\Command;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Store\Command\StoreLoginCommand;
use SnapAdmin\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 */
class StoreLoginCommandTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function testEmptyPasswordOption(): void
    {
        $commandTester = new CommandTester($this->getContainer()->get(StoreLoginCommand::class));

        static::expectException(\RuntimeException::class);
        static::expectExceptionMessage('The password cannot be empty');

        $commandTester->setInputs(['', '', '']);
        $commandTester->execute([
            '--snapId' => 'no-reply@snapadmin.de',
            '--user' => 'missing_user',
        ]);
    }

    public function testValidPasswordOptionInvalidUserOption(): void
    {
        $commandTester = new CommandTester($this->getContainer()->get(StoreLoginCommand::class));

        static::expectException(\RuntimeException::class);
        static::expectExceptionMessage('User not found');

        $commandTester->setInputs(['non-empty-password']);
        $commandTester->execute([
            '--snapId' => 'no-reply@snapadmin.de',
            '--user' => 'missing_user',
        ]);
    }
}
