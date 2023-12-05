<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command\WriteCommand;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command\WriteCommandQueue;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
interface EntityWriteGatewayInterface
{
    public function prefetchExistences(WriteParameterBag $parameterBag): void;

    /**
     * @param array<string, string> $primaryKey
     * @param array<string, mixed> $data
     */
    public function getExistence(EntityDefinition $definition, array $primaryKey, array $data, WriteCommandQueue $commandQueue): EntityExistence;

    /**
     * @param list<WriteCommand> $commands
     */
    public function execute(array $commands, WriteContext $context): void;
}
