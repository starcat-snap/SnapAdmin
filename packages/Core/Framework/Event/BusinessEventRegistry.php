<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use SnapAdmin\Core\Checkout\Customer\Event\CustomerAccountRecoverRequestEvent;
use SnapAdmin\Core\Checkout\Customer\Event\CustomerBeforeLoginEvent;
use SnapAdmin\Core\Checkout\Customer\Event\CustomerChangedPaymentMethodEvent;
use SnapAdmin\Core\Checkout\Customer\Event\CustomerDeletedEvent;
use SnapAdmin\Core\Checkout\Customer\Event\CustomerDoubleOptInRegistrationEvent;
use SnapAdmin\Core\Checkout\Customer\Event\CustomerGroupRegistrationAccepted;
use SnapAdmin\Core\Checkout\Customer\Event\CustomerGroupRegistrationDeclined;
use SnapAdmin\Core\Checkout\Customer\Event\CustomerLoginEvent;
use SnapAdmin\Core\Checkout\Customer\Event\CustomerLogoutEvent;
use SnapAdmin\Core\Checkout\Customer\Event\CustomerRegisterEvent;
use SnapAdmin\Core\Checkout\Customer\Event\DoubleOptInGuestOrderEvent;
use SnapAdmin\Core\Checkout\Customer\Event\GuestCustomerRegisterEvent;
use SnapAdmin\Core\Checkout\Order\Event\OrderPaymentMethodChangedEvent;
use SnapAdmin\Core\Content\ContactForm\Event\ContactFormEvent;
use SnapAdmin\Core\Content\MailTemplate\Service\Event\MailBeforeSentEvent;
use SnapAdmin\Core\Content\MailTemplate\Service\Event\MailBeforeValidateEvent;
use SnapAdmin\Core\Content\MailTemplate\Service\Event\MailSentEvent;
use SnapAdmin\Core\Content\Newsletter\Event\NewsletterConfirmEvent;
use SnapAdmin\Core\Content\Newsletter\Event\NewsletterRegisterEvent;
use SnapAdmin\Core\Content\Newsletter\Event\NewsletterUnsubscribeEvent;
use SnapAdmin\Core\Content\Product\SalesChannel\Review\Event\ReviewFormEvent;
use SnapAdmin\Core\Content\ProductExport\Event\ProductExportLoggingEvent;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\User\Recovery\UserRecoveryRequestEvent;

#[Package('business-ops')]
class BusinessEventRegistry
{
    /**
     * @var array<string>
     */
    private array $classes = [
        CustomerBeforeLoginEvent::class,
        CustomerLoginEvent::class,
        CustomerLogoutEvent::class,
        CustomerDeletedEvent::class,
        UserRecoveryRequestEvent::class,
        CustomerChangedPaymentMethodEvent::class,
        CheckoutOrderPlacedEvent::class,
        OrderPaymentMethodChangedEvent::class,
        CustomerAccountRecoverRequestEvent::class,
        CustomerDoubleOptInRegistrationEvent::class,
        CustomerGroupRegistrationAccepted::class,
        CustomerGroupRegistrationDeclined::class,
        CustomerRegisterEvent::class,
        DoubleOptInGuestOrderEvent::class,
        GuestCustomerRegisterEvent::class,
        ContactFormEvent::class,
        ReviewFormEvent::class,
        MailBeforeSentEvent::class,
        MailBeforeValidateEvent::class,
        MailSentEvent::class,
        NewsletterConfirmEvent::class,
        NewsletterRegisterEvent::class,
        NewsletterUnsubscribeEvent::class,
        ProductExportLoggingEvent::class,
    ];

    public function addClasses(array $classes): void
    {
        $this->classes = array_unique(array_merge($this->classes, $classes));
    }

    public function getClasses(): array
    {
        return $this->classes;
    }
}
