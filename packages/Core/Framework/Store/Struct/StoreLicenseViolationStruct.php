<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @codeCoverageIgnore
 */
#[Package('services-settings')]
class StoreLicenseViolationStruct extends Struct
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var StoreLicenseViolationTypeStruct
     */
    protected $type;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var StoreActionStruct[]
     */
    protected $actions;

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): StoreLicenseViolationTypeStruct
    {
        return $this->type;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getApiAlias(): string
    {
        return 'store_license_violation';
    }
}
