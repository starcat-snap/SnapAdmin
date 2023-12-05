<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Context;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class ShopApiSource extends SalesChannelApiSource
{
    public string $type = 'shop-api';
}
