<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @codeCoverageIgnore
 */
#[Package('services-settings')]
class StoreLicenseTypeStruct extends Struct
{
    /**
     * @var string
     */
    protected $name;

    public function getApiAlias(): string
    {
        return 'store_license_type';
    }
}
