<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<StateMachineTranslationEntity>
 */
#[Package('core')]
class StateMachineTranslationCollection extends EntityCollection
{
    /**
     * @return array<string>
     */
    public function getLanguageIds(): array
    {
        return $this->fmap(fn (StateMachineTranslationEntity $stateMachineTranslation) => $stateMachineTranslation->getLanguageId());
    }

    public function filterByLanguageId(string $id): self
    {
        return $this->filter(fn (StateMachineTranslationEntity $stateMachineTranslation) => $stateMachineTranslation->getLanguageId() === $id);
    }

    public function getApiAlias(): string
    {
        return 'state_machine_translation_collection';
    }

    protected function getExpectedClass(): string
    {
        return StateMachineTranslationEntity::class;
    }
}
