<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @codeCoverageIgnore
 */
#[Package('services-settings')]
class AccessTokenStruct extends Struct
{
    public function __construct(
        protected ShopUserTokenStruct $shopUserToken,
        protected ?string $shopSecret = null,
    ) {
    }

    public function getShopUserToken(): ShopUserTokenStruct
    {
        return $this->shopUserToken;
    }

    public function getShopSecret(): ?string
    {
        return $this->shopSecret;
    }

    public function getApiAlias(): string
    {
        return 'store_access_token';
    }
}
