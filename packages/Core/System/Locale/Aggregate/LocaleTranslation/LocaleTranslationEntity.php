<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Locale\Aggregate\LocaleTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\TranslationEntity;
use SnapAdmin\Core\System\Locale\LocaleEntity;

class LocaleTranslationEntity extends TranslationEntity
{
    use EntityCustomFieldsTrait;

    /**
     * @var string
     */
    protected $localeId;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $territory;

    /**
     * @var LocaleEntity|null
     */
    protected $locale;

    public function getLocaleId(): string
    {
        return $this->localeId;
    }

    public function setLocaleId(string $localeId): void
    {
        $this->localeId = $localeId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getTerritory(): ?string
    {
        return $this->territory;
    }

    public function setTerritory(string $territory): void
    {
        $this->territory = $territory;
    }

    public function getLocale(): ?LocaleEntity
    {
        return $this->locale;
    }

    public function setLocale(LocaleEntity $locale): void
    {
        $this->locale = $locale;
    }
}
