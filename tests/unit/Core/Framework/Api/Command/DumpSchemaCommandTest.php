<?php declare(strict_types=1);

namespace SnapAdmin\Tests\Unit\Core\Framework\Api\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Api\ApiDefinition\DefinitionService;
use SnapAdmin\Core\Framework\Api\ApiDefinition\Generator\EntitySchemaGenerator;
use SnapAdmin\Core\Framework\Api\Command\DumpSchemaCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 */
#[CoversClass(DumpSchemaCommand::class)]
class DumpSchemaCommandTest extends TestCase
{
    public function testSimpleCall(): void
    {
        $definitionService = $this->createMock(DefinitionService::class);
        $definitionService->expects(static::once())->method('getSchema');
        $cmd = new DumpSchemaCommand($definitionService);

        $cmd = new CommandTester($cmd);
        $cmd->execute(['outfile' => '-'], ['capture_stderr_separately' => true]);

        $cmd->assertCommandIsSuccessful();
        static::assertNotEmpty($cmd->getErrorOutput(), 'no status messages in stderr found');
    }

    public function testEntitySchema(): void
    {
        $definitionService = $this->createMock(DefinitionService::class);
        $definitionService->expects(static::once())->method('getSchema')->with(EntitySchemaGenerator::FORMAT, DefinitionService::API);
        $cmd = new DumpSchemaCommand($definitionService);

        $cmd = new CommandTester($cmd);
        $cmd->execute(['outfile' => '/dev/null',  '--schema-format' => 'entity-schema']);

        $cmd->assertCommandIsSuccessful();
    }
}
