<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Command;

use SnapAdmin\Core\Framework\Api\ApiDefinition\DefinitionService;
use SnapAdmin\Core\Framework\Api\ApiDefinition\Generator\EntitySchemaGenerator;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'framework:schema',
    description: 'Dumps the schema of the given entity',
)]
#[Package('core')]
class DumpSchemaCommand extends Command
{
    /**
     * @internal
     */
    public function __construct(private readonly DefinitionService $definitionService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('outfile', InputArgument::REQUIRED, 'Path to the output file. "-" writes to stdout.')
            ->addOption(
                'schema-format',
                's',
                InputOption::VALUE_REQUIRED,
                'The format of the dumped definition. Either "simple", "openapi3" or "entity-schema.',
                'simple'
            )
            ->addOption(
                'frontend-api',
                '',
                InputOption::VALUE_NONE,
                'If set, the store api definition will be dumped. Only applies to the openapi3 format.'
            )
            ->addOption('pretty', 'p', InputOption::VALUE_NONE, 'Dumps the output in a human-readable form.')
            ->addOption('bundle-name', 'b', InputOption::VALUE_OPTIONAL, 'Only uses definitions from a specific bundle.', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outFile = $input->getArgument('outfile');
        if ($outFile === '-') {
            $outFile = 'php://stdout';
            $output = $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output;
        }
        $formatType = $input->getOption('schema-format');
        $bundleName = (empty($input->getOption('bundle-name'))) ? '' : $input->getOption('bundle-name');

        $definitionContents = match ($formatType) {
            'simple' => $this->definitionService->getSchema(),
            'entity-schema' => $this->definitionService->getSchema(EntitySchemaGenerator::FORMAT, DefinitionService::API),
            default => throw new \InvalidArgumentException('Invalid "format-type" given. Aborting.'),
        };

        $jsonFlags = $input->getOption('pretty') ? \JSON_PRETTY_PRINT : 0;

        $output->writeln('Writing definition to file ...');
        file_put_contents($outFile, json_encode($definitionContents, $jsonFlags));
        $output->writeln('Done!');

        return self::SUCCESS;
    }
}
