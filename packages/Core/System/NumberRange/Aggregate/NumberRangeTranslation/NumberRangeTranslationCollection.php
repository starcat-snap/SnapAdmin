<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\Aggregate\NumberRangeTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<NumberRangeTranslationEntity>
 */
#[Package('system-settings')]
class NumberRangeTranslationCollection extends EntityCollection
{
    /**
     * @return array<string>
     */
    public function getNumberRangeIds(): array
    {
        return $this->fmap(fn (NumberRangeTranslationEntity $numberRangeTranslation) => $numberRangeTranslation->getNumberRangeId());
    }

    public function filterByNumberRangeId(string $id): self
    {
        return $this->filter(fn (NumberRangeTranslationEntity $numberRangeTranslation) => $numberRangeTranslation->getNumberRangeId() === $id);
    }

    /**
     * @return array<string>
     */
    public function getLanguageIds(): array
    {
        return $this->fmap(fn (NumberRangeTranslationEntity $numberRangeTranslation) => $numberRangeTranslation->getLanguageId());
    }

    public function filterByLanguageId(string $id): self
    {
        return $this->filter(fn (NumberRangeTranslationEntity $numberRangeTranslation) => $numberRangeTranslation->getLanguageId() === $id);
    }

    public function getApiAlias(): string
    {
        return 'number_range_translation_collection';
    }

    protected function getExpectedClass(): string
    {
        return NumberRangeTranslationEntity::class;
    }
}
