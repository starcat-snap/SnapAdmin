<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Context;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

#[DiscriminatorMap(typeProperty: 'type', mapping: ['system' => SystemSource::class, 'sales-channel' => SalesChannelApiSource::class, 'admin-api' => AdminApiSource::class, 'shop-api' => ShopApiSource::class, 'admin-sales-channel-api' => AdminSalesChannelApiSource::class])]
#[Package('core')]
interface ContextSource
{
}
