<?php declare(strict_types=1);

namespace SnapAdmin\Core\Installer\Requirements;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Installer\Requirements\Struct\RequirementsCheckCollection;

/**
 * @internal
 */
#[Package('core')]
interface RequirementsValidatorInterface
{
    public function validateRequirements(RequirementsCheckCollection $checks): RequirementsCheckCollection;
}
