<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeTypeTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\TranslationEntity;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeType\NumberRangeTypeEntity;

#[Package('system-settings')]
class NumberRangeTypeTranslationEntity extends TranslationEntity
{
    use EntityCustomFieldsTrait;

    /**
     * @var string
     */
    protected $numberRangeTypeId;

    /**
     * @var string|null
     */
    protected $typeName;

    /**
     * @var NumberRangeTypeEntity|null
     */
    protected $numberRangeType;

    public function getNumberRangeTypeId(): string
    {
        return $this->numberRangeTypeId;
    }

    public function setNumberRangeTypeId(string $numberRangeTypeId): void
    {
        $this->numberRangeTypeId = $numberRangeTypeId;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(?string $typeName): void
    {
        $this->typeName = $typeName;
    }

    public function getNumberRangeType(): ?NumberRangeTypeEntity
    {
        return $this->numberRangeType;
    }

    public function setNumberRangeType(?NumberRangeTypeEntity $numberRangeType): void
    {
        $this->numberRangeType = $numberRangeType;
    }
}
