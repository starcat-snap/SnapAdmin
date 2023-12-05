<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Command\Scaffolding\Generator;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
trait HasCommandOption
{
    public function hasCommandOption(): bool
    {
        return true;
    }

    public function getCommandOptionName(): string
    {
        return self::OPTION_NAME;
    }

    public function getCommandOptionDescription(): string
    {
        return self::OPTION_DESCRIPTION;
    }
}
