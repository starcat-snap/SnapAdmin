<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @deprecated tag:v6.6.0 - reason:class-hierarchy-change - extends of FlowEventAware will be removed, implement the interface inside your event
 */
#[Package('business-ops')]
interface ProductAware extends FlowEventAware
{
    public const PRODUCT = 'product';

    public const PRODUCT_ID = 'productId';

    public function getProductId(): string;
}
