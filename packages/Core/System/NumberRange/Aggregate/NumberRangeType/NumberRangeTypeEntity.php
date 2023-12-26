<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeType;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeTypeTranslation\NumberRangeTypeTranslationCollection;
use SnapAdmin\Core\System\NumberRange\NumberRangeCollection;

#[Package('checkout')]
class NumberRangeTypeEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $typeName;

    /**
     * @var string
     */
    protected $technicalName;

    /**
     * @var bool
     */
    protected $global;

    /**
     * @var NumberRangeCollection|null
     */
    protected $numberRanges;


    /**
     * @var NumberRangeTypeTranslationCollection|null
     */
    protected $translations;

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): void
    {
        $this->typeName = $typeName;
    }

    public function getGlobal(): bool
    {
        return $this->global;
    }

    public function setGlobal(bool $global): void
    {
        $this->global = $global;
    }

    public function getNumberRanges(): ?NumberRangeCollection
    {
        return $this->numberRanges;
    }

    public function setNumberRanges(NumberRangeCollection $numberRanges): void
    {
        $this->numberRanges = $numberRanges;
    }

    public function getTranslations(): ?NumberRangeTypeTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(NumberRangeTypeTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getTechnicalName(): string
    {
        return $this->technicalName;
    }

    public function setTechnicalName(string $technicalName): void
    {
        $this->technicalName = $technicalName;
    }
}
