<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeState;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\NumberRange\NumberRangeEntity;

#[Package('checkout')]
class NumberRangeStateEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $numberRangeId;

    /**
     * @var int
     */
    protected $lastValue;

    /**
     * @var NumberRangeEntity|null
     */
    protected $numberRange;

    public function getNumberRangeId(): string
    {
        return $this->numberRangeId;
    }

    public function setNumberRangeId(string $numberRangeId): void
    {
        $this->numberRangeId = $numberRangeId;
    }

    public function getLastValue(): int
    {
        return $this->lastValue;
    }

    public function setLastValue(int $lastValue): void
    {
        $this->lastValue = $lastValue;
    }

    public function getNumberRange(): ?NumberRangeEntity
    {
        return $this->numberRange;
    }

    public function setNumberRange(?NumberRangeEntity $numberRange): void
    {
        $this->numberRange = $numberRange;
    }
}
