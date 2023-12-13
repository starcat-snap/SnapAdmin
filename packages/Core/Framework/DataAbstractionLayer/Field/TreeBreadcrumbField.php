<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class TreeBreadcrumbField extends JsonField
{
    public function __construct(
        string $storageName = 'breadcrumb',
        string $propertyName = 'breadcrumb',
        private readonly string $nameField = 'name'
    ) {
        parent::__construct($storageName, $propertyName);

        $this->addFlags(new WriteProtected(Context::SYSTEM_SCOPE));
    }

    public function getNameField(): string
    {
        return $this->nameField;
    }
}
