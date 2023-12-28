<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Rule;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RuleAreas;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<RuleEntity>
 */
#[Package('services-settings')]
class RuleCollection extends EntityCollection
{
    public function filterForContext(): self
    {
        return $this->filter(
            fn (RuleEntity $rule) => !$rule->getAreas() || !\in_array(RuleAreas::FLOW_CONDITION_AREA, $rule->getAreas(), true)
        );
    }

    public function filterForFlow(): self
    {
        return $this->filter(
            fn (RuleEntity $rule) => $rule->getAreas() && \in_array(RuleAreas::FLOW_AREA, $rule->getAreas(), true)
        );
    }

    /**
     * @return array<string, string[]>
     */
    public function getIdsByArea(): array
    {
        $idsByArea = [];

        foreach ($this->getElements() as $rule) {
            foreach ($rule->getAreas() ?? [] as $area) {
                $idsByArea[$area] = array_unique(array_merge($idsByArea[$area] ?? [], [$rule->getId()]));
            }
        }

        return $idsByArea;
    }

    public function sortByPriority(): void
    {
        $this->sort(fn (RuleEntity $a, RuleEntity $b) => $b->getPriority() <=> $a->getPriority());
    }

    public function equals(RuleCollection $rules): bool
    {
        if ($this->count() !== $rules->count()) {
            return false;
        }

        foreach ($this->elements as $element) {
            if (!$rules->has($element->getId())) {
                return false;
            }
        }

        return true;
    }

    public function getApiAlias(): string
    {
        return 'rule_collection';
    }

    protected function getExpectedClass(): string
    {
        return RuleEntity::class;
    }
}
