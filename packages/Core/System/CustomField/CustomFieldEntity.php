<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomField;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\CustomField\Aggregate\CustomFieldSet\CustomFieldSetEntity;

#[Package('system-settings')]
class CustomFieldEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array<string, mixed>|null
     */
    protected $config;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var string|null
     */
    protected $customFieldSetId;

    /**
     * @var CustomFieldSetEntity|null
     */
    protected $customFieldSet;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getConfig(): ?array
    {
        return $this->config;
    }

    /**
     * @param array<string, mixed>|null $config
     */
    public function setConfig(?array $config): void
    {
        $this->config = $config;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getCustomFieldSetId(): ?string
    {
        return $this->customFieldSetId;
    }

    public function setCustomFieldSetId(?string $attributeSetId): void
    {
        $this->customFieldSetId = $attributeSetId;
    }

    public function getCustomFieldSet(): ?CustomFieldSetEntity
    {
        return $this->customFieldSet;
    }

    public function setCustomFieldSet(?CustomFieldSetEntity $attributeSet): void
    {
        $this->customFieldSet = $attributeSet;
    }
}
