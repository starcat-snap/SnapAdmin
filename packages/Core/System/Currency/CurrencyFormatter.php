<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Currency;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Locale\LanguageLocaleCodeProvider;
use Symfony\Contracts\Service\ResetInterface;

#[Package('system')]
class CurrencyFormatter implements ResetInterface
{
    /**
     * @var \NumberFormatter[]
     */
    private array $formatter = [];

    /**
     * @internal
     */
    public function __construct(private readonly LanguageLocaleCodeProvider $languageLocaleProvider)
    {
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function formatCurrencyByLanguage(float $price, string $currency, string $languageId, Context $context, ?int $decimals = null): string
    {
        $decimals ??= $context->getRounding()->getDecimals();

        $formatter = $this->getFormatter(
            $this->languageLocaleProvider->getLocaleForLanguageId($languageId)
        );
        $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, $decimals);

        return (string) $formatter->formatCurrency($price, $currency);
    }

    public function reset(): void
    {
        $this->formatter = [];
    }

    private function getFormatter(string $locale): \NumberFormatter
    {
        if (isset($this->formatter[$locale])) {
            return $this->formatter[$locale];
        }

        return $this->formatter[$locale] = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
    }
}
