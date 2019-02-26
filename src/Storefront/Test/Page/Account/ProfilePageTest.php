<?php declare(strict_types=1);

namespace Shopware\Storefront\Test\Page\Account;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Routing\InternalRequest;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Storefront\Framework\Page\PageLoaderInterface;
use Shopware\Storefront\Page\Account\Profile\AccountProfilePage;
use Shopware\Storefront\Page\Account\Profile\AccountProfilePageLoadedEvent;
use Shopware\Storefront\Page\Account\Profile\AccountProfilePageLoader;
use Shopware\Storefront\Test\Page\StorefrontPageTestBehaviour;

class ProfilePageTest extends TestCase
{
    use IntegrationTestBehaviour,
        StorefrontPageTestBehaviour;

    public function testItThrowsWithoutNavigation(): void
    {
        $this->assertFailsWithoutNavigation();
    }

    public function testLoginRequirement(): void
    {
        $this->assertLoginRequirement();
    }

    public function testItLoadsTheProfilePage(): void
    {
        $request = new InternalRequest();
        $context = $this->createCheckoutContextWithLoggedInCustomerAndWithNavigation();

        /** @var AccountProfilePageLoadedEvent $event */
        $event = null;
        $this->catchEvent(AccountProfilePageLoadedEvent::NAME, $event);

        $page = $this->getPageLoader()->load($request, $context);

        static::assertInstanceOf(AccountProfilePage::class, $page);
        static::assertSame('Max', $page->getCustomer()->getFirstName());
        self::assertPageEvent(AccountProfilePageLoadedEvent::class, $event, $context, $request, $page);
    }

    /**
     * @return AccountProfilePageLoader
     */
    protected function getPageLoader(): PageLoaderInterface
    {
        return $this->getContainer()->get(AccountProfilePageLoader::class);
    }
}
