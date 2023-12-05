<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Services;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Exception\DecorationPatternException;
use SnapAdmin\Core\Framework\Store\Struct\ReviewStruct;

/**
 * @internal
 */
#[Package('services-settings')]
class ExtensionStoreLicensesService extends AbstractExtensionStoreLicensesService
{
    public function __construct(private readonly StoreClient $client)
    {
    }

    public function cancelSubscription(int $licenseId, Context $context): void
    {
        $this->client->cancelSubscription($licenseId, $context);
    }

    public function rateLicensedExtension(ReviewStruct $rating, Context $context): void
    {
        $this->client->createRating($rating, $context);
    }

    /**
     * @codeCoverageIgnore
     */
    protected function getDecorated(): AbstractExtensionStoreLicensesService
    {
        throw new DecorationPatternException(self::class);
    }
}
