<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command\WriteCommand;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class UnsupportedCommandTypeException extends SnapAdminHttpException
{
    public function __construct(WriteCommand $command)
    {
        parent::__construct(
            'Command of class {{ command }} is not supported by {{ definition }}',
            ['command' => $command::class, 'definition' => $command->getDefinition()->getEntityName()]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__UNSUPPORTED_COMMAND_TYPE_EXCEPTION';
    }
}
