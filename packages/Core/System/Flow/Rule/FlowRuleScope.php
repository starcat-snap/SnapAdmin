<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Rule;

use SnapAdmin\Core\Checkout\Cart\Cart;
use SnapAdmin\Core\Checkout\Cart\Rule\CartRuleScope;
use SnapAdmin\Core\Checkout\Order\OrderEntity;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;

#[Package('services-settings')]
class FlowRuleScope extends CartRuleScope
{
    public function __construct(
        private readonly OrderEntity $order,
        Cart $cart,
        SalesChannelContext $context
    ) {
        parent::__construct($cart, $context);
    }

    public function getOrder(): OrderEntity
    {
        return $this->order;
    }
}
