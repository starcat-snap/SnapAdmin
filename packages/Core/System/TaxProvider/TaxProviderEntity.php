<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\TaxProvider;

use SnapAdmin\Core\Content\Rule\RuleEntity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\TaxProvider\Aggregate\TaxProviderTranslation\TaxProviderTranslationCollection;

#[Package('checkout')]
class TaxProviderEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    protected ?string $name = null;

    protected bool $active;

    protected int $priority;

    protected string $identifier;

    protected ?string $availabilityRuleId = null;

    protected ?RuleEntity $availabilityRule = null;

    protected ?TaxProviderTranslationCollection $translations = null;

    protected ?string $processUrl = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getAvailabilityRuleId(): ?string
    {
        return $this->availabilityRuleId;
    }

    public function setAvailabilityRuleId(?string $availabilityRuleId): void
    {
        $this->availabilityRuleId = $availabilityRuleId;
    }

    public function getAvailabilityRule(): ?RuleEntity
    {
        return $this->availabilityRule;
    }

    public function setAvailabilityRule(RuleEntity $availabilityRule): void
    {
        $this->availabilityRule = $availabilityRule;
    }

    public function getTranslations(): ?TaxProviderTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(TaxProviderTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function setAppId(?string $appId): void
    {
        $this->appId = $appId;
    }

    public function getProcessUrl(): ?string
    {
        return $this->processUrl;
    }

    public function setProcessUrl(?string $processUrl): void
    {
        $this->processUrl = $processUrl;
    }
}
