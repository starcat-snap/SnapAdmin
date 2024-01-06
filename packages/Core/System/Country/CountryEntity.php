<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Country\Aggregate\CountryState\CountryStateCollection;
use SnapAdmin\Core\System\Country\Aggregate\CountryTranslation\CountryTranslationCollection;

#[Package('system')]
class CountryEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $iso;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var string|null
     */
    protected $iso3;

    /**
     * @var bool
     */
    protected $displayStateInRegistration;

    /**
     * @var bool
     */
    protected $forceStateInRegistration;


    /**
     * @var CountryStateCollection|null
     */
    protected $states;

    /**
     * @var CountryTranslationCollection|null
     */
    protected $translations;


    protected bool $postalCodeRequired;

    protected bool $checkPostalCodePattern;

    protected bool $checkAdvancedPostalCodePattern;

    protected ?string $advancedPostalCodePattern = null;

    protected ?string $defaultPostalCodePattern = null;

    /**
     * @var array<array<string, array<string, string>>>
     */
    protected array $addressFormat;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getIso(): ?string
    {
        return $this->iso;
    }

    public function setIso(?string $iso): void
    {
        $this->iso = $iso;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    public function setIso3(?string $iso3): void
    {
        $this->iso3 = $iso3;
    }

    public function getDisplayStateInRegistration(): bool
    {
        return $this->displayStateInRegistration;
    }

    public function setDisplayStateInRegistration(bool $displayStateInRegistration): void
    {
        $this->displayStateInRegistration = $displayStateInRegistration;
    }

    public function getForceStateInRegistration(): bool
    {
        return $this->forceStateInRegistration;
    }

    public function setForceStateInRegistration(bool $forceStateInRegistration): void
    {
        $this->forceStateInRegistration = $forceStateInRegistration;
    }


    public function getStates(): ?CountryStateCollection
    {
        return $this->states;
    }

    public function setStates(CountryStateCollection $states): void
    {
        $this->states = $states;
    }

    public function getTranslations(): ?CountryTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(CountryTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getPostalCodeRequired(): bool
    {
        return $this->postalCodeRequired;
    }

    public function setPostalCodeRequired(bool $postalCodeRequired): void
    {
        $this->postalCodeRequired = $postalCodeRequired;
    }

    public function getCheckPostalCodePattern(): bool
    {
        return $this->checkPostalCodePattern;
    }

    public function setCheckPostalCodePattern(bool $checkPostalCodePattern): void
    {
        $this->checkPostalCodePattern = $checkPostalCodePattern;
    }

    public function getCheckAdvancedPostalCodePattern(): bool
    {
        return $this->checkAdvancedPostalCodePattern;
    }

    public function setCheckAdvancedPostalCodePattern(bool $checkAdvancedPostalCodePattern): void
    {
        $this->checkAdvancedPostalCodePattern = $checkAdvancedPostalCodePattern;
    }

    public function getAdvancedPostalCodePattern(): ?string
    {
        return $this->advancedPostalCodePattern;
    }

    public function setAdvancedPostalCodePattern(?string $advancedPostalCodePattern): void
    {
        $this->advancedPostalCodePattern = $advancedPostalCodePattern;
    }

    /**
     * @return array<array<string, array<string, string>>>
     */
    public function getAddressFormat(): array
    {
        return $this->addressFormat;
    }

    /**
     * @param array<array<string, array<string, string>>> $addressFormat
     */
    public function setAddressFormat(array $addressFormat): void
    {
        $this->addressFormat = $addressFormat;
    }

    public function setDefaultPostalCodePattern(?string $pattern): void
    {
        $this->defaultPostalCodePattern = $pattern;
    }

    public function getDefaultPostalCodePattern(): ?string
    {
        return $this->defaultPostalCodePattern;
    }
}
