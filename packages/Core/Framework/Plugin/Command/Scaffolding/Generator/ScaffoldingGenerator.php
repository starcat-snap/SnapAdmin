<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Command\Scaffolding\Generator;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Command\Scaffolding\PluginScaffoldConfiguration;
use SnapAdmin\Core\Framework\Plugin\Command\Scaffolding\StubCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @internal
 */
#[Package('core')]
interface ScaffoldingGenerator
{
    public const STUB_DIRECTORY = __DIR__ . '/../stubs';

    public function hasCommandOption(): bool;

    public function getCommandOptionName(): string;

    public function getCommandOptionDescription(): string;

    public function addScaffoldConfig(
        PluginScaffoldConfiguration $config,
        InputInterface              $input,
        SymfonyStyle                $io
    ): void;

    public function generateStubs(PluginScaffoldConfiguration $configuration, StubCollection $stubCollection): void;
}
