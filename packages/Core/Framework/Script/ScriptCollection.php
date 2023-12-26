<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal only for use by the app-system
 *
 * @extends EntityCollection<ScriptEntity>
 */
#[Package('core')]
class ScriptCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ScriptEntity::class;
    }
}
