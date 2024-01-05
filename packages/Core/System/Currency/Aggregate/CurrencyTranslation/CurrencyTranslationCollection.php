<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Currency\Aggregate\CurrencyTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<CurrencyTranslationEntity>
 */
#[Package('system')]
class CurrencyTranslationCollection extends EntityCollection
{
    public function getCurrencyIds(): array
    {
        return $this->fmap(fn (CurrencyTranslationEntity $currencyTranslation) => $currencyTranslation->getCurrencyId());
    }

    public function filterByCurrencyId(string $id): self
    {
        return $this->filter(fn (CurrencyTranslationEntity $currencyTranslation) => $currencyTranslation->getCurrencyId() === $id);
    }

    public function getLanguageIds(): array
    {
        return $this->fmap(fn (CurrencyTranslationEntity $currencyTranslation) => $currencyTranslation->getLanguageId());
    }

    public function filterByLanguageId(string $id): self
    {
        return $this->filter(fn (CurrencyTranslationEntity $currencyTranslation) => $currencyTranslation->getLanguageId() === $id);
    }

    public function getApiAlias(): string
    {
        return 'currency_translation_collection';
    }

    protected function getExpectedClass(): string
    {
        return CurrencyTranslationEntity::class;
    }
}
