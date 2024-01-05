<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\Aggregate\TaxRuleType;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRule\TaxRuleCollection;
use SnapAdmin\Core\System\Tax\Aggregate\TaxRuleTypeTranslation\TaxRuleTypeTranslationCollection;

#[Package('checkout')]
class TaxRuleTypeEntity extends Entity
{
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
     * @var int
     */
    protected $position;

    /**
     * @var TaxRuleCollection|null
     */
    protected $rules;

    /**
     * @var TaxRuleTypeTranslationCollection|null
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

    public function getTechnicalName(): string
    {
        return $this->technicalName;
    }

    public function setTechnicalName(string $technicalName): void
    {
        $this->technicalName = $technicalName;
    }

    public function getRules(): ?TaxRuleCollection
    {
        return $this->rules;
    }

    public function setRules(TaxRuleCollection $rules): void
    {
        $this->rules = $rules;
    }

    public function getTranslations(): ?TaxRuleTypeTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(TaxRuleTypeTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }
}
