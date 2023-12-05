<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @codeCoverageIgnore
 */
#[Package('services-settings')]
class ShopUserTokenStruct extends Struct
{
    public function __construct(
        protected string             $token,
        protected \DateTimeInterface $expirationDate,
    )
    {
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpirationDate(): \DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function getApiAlias(): string
    {
        return 'store_shop_user_token';
    }
}
