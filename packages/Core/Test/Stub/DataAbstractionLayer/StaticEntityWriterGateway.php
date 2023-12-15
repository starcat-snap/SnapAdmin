<?php declare(strict_types=1);

namespace SnapAdmin\Core\Test\Stub\DataAbstractionLayer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command\WriteCommandQueue;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityWriteGatewayInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteContext;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;

/**
 * @final
 */
class StaticEntityWriterGateway implements EntityWriteGatewayInterface
{
    public function prefetchExistences(WriteParameterBag $parameterBag): void
    {
    }

    public function getExistence(EntityDefinition $definition, array $primaryKey, array $data, WriteCommandQueue $commandQueue): EntityExistence
    {
        return new EntityExistence($definition->getEntityName(), $primaryKey, false, false, false, []);
    }

    public function execute(array $commands, WriteContext $context): void
    {
    }
}
