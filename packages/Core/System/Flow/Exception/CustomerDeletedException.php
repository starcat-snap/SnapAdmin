<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Exception;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class CustomerDeletedException extends \Exception
{
    public function __construct(string $orderId)
    {
        $message = sprintf('The Customer of Order Id %s has been deleted', $orderId);

        parent::__construct($message);
    }
}
