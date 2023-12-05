<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Computed;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class LockedField extends BoolField
{
    public function __construct()
    {
        parent::__construct('locked', 'locked');

        $this->addFlags(new Computed());
    }
}
