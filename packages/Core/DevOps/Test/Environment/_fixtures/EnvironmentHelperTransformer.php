<?php declare(strict_types=1);

namespace SnapAdmin\Core\DevOps\Test\Environment\_fixtures;

use SnapAdmin\Core\DevOps\Environment\EnvironmentHelperTransformerData;
use SnapAdmin\Core\DevOps\Environment\EnvironmentHelperTransformerInterface;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class EnvironmentHelperTransformer implements EnvironmentHelperTransformerInterface
{
    public static function transform(EnvironmentHelperTransformerData $data): void
    {
        $data->setValue($data->getValue() !== null ? $data->getValue() . ' bar' : null);
        $data->setDefault($data->getDefault() . ' baz');
    }
}
