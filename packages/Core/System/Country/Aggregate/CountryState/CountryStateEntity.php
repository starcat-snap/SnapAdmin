<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country\Aggregate\CountryState;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Country\Aggregate\CountryStateTranslation\CountryStateCityTranslationCollection;
use SnapAdmin\Core\System\Country\CountryEntity;

#[Package('system')]
class CountryStateEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $countryId;

    /**
     * @var string
     */
    protected $shortCode;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var CountryEntity|null
     */
    protected $country;

    /**
     * @var CountryStateCityTranslationCollection|null
     */
    protected $translations;

    public function getCountryId(): string
    {
        return $this->countryId;
    }

    public function setCountryId(string $countryId): void
    {
        $this->countryId = $countryId;
    }

    public function getShortCode(): string
    {
        return $this->shortCode;
    }

    public function setShortCode(string $shortCode): void
    {
        $this->shortCode = $shortCode;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
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

    public function getCountry(): ?CountryEntity
    {
        return $this->country;
    }

    public function setCountry(CountryEntity $country): void
    {
        $this->country = $country;
    }

    public function getTranslations(): ?CountryStateCityTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(CountryStateCityTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }
}
