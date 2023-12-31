<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @codeCoverageIgnore
 */
#[Package('services-settings')]
class LicenseDomainStruct extends Struct
{
    /**
     * @var string
     */
    protected $domain;

    /**
     * @var bool
     */
    protected $verified = false;

    /**
     * @var string
     */
    protected $edition = 'Community Edition';

    /**
     * @var bool
     */
    protected $active = false;

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function getEdition(): string
    {
        return $this->edition;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getApiAlias(): string
    {
        return 'store_license_domain';
    }
}
