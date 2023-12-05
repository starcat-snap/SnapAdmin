<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Locale\Aggregate\LocaleTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<LocaleTranslationEntity>
 */
#[Package('buyers-experience')]
class LocaleTranslationCollection extends EntityCollection
{
    public function getLocaleIds(): array
    {
        return $this->fmap(fn(LocaleTranslationEntity $localeTranslation) => $localeTranslation->getLocaleId());
    }

    public function filterByLocaleId(string $id): self
    {
        return $this->filter(fn(LocaleTranslationEntity $localeTranslation) => $localeTranslation->getLocaleId() === $id);
    }

    public function getLanguageIds(): array
    {
        return $this->fmap(fn(LocaleTranslationEntity $localeTranslation) => $localeTranslation->getLanguageId());
    }

    public function filterByLanguageId(string $id): self
    {
        return $this->filter(fn(LocaleTranslationEntity $localeTranslation) => $localeTranslation->getLanguageId() === $id);
    }

    public function getApiAlias(): string
    {
        return 'locale_translation_collection';
    }

    protected function getExpectedClass(): string
    {
        return LocaleTranslationEntity::class;
    }
}
