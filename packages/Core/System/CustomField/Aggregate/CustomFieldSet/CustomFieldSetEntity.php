<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomField\Aggregate\CustomFieldSet;

use SnapAdmin\Core\Content\Product\ProductCollection;
use SnapAdmin\Core\Framework\App\AppEntity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\CustomField\Aggregate\CustomFieldSetRelation\CustomFieldSetRelationCollection;
use SnapAdmin\Core\System\CustomField\CustomFieldCollection;

#[Package('system-settings')]
class CustomFieldSetEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|null
     */
    protected $config;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var bool
     */
    protected $global;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var CustomFieldCollection|null
     */
    protected $customFields;

    /**
     * @var CustomFieldSetRelationCollection|null
     */
    protected $relations;

    /**
     * @var ProductCollection|null
     */
    protected $products;

    /**
     * @var string|null
     */
    protected $appId;

    /**
     * @var AppEntity|null
     */
    protected $app;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getConfig(): ?array
    {
        return $this->config;
    }

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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getCustomFields(): ?CustomFieldCollection
    {
        return $this->customFields;
    }

    public function setCustomFields(CustomFieldCollection $customFields): void
    {
        $this->customFields = $customFields;
    }

    public function getRelations(): ?CustomFieldSetRelationCollection
    {
        return $this->relations;
    }

    public function setRelations(CustomFieldSetRelationCollection $relations): void
    {
        $this->relations = $relations;
    }

    public function getProducts(): ?ProductCollection
    {
        return $this->products;
    }

    public function setProducts(ProductCollection $products): void
    {
        $this->products = $products;
    }

    public function isGlobal(): bool
    {
        return $this->global;
    }

    public function setGlobal(bool $global): void
    {
        $this->global = $global;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function setAppId(?string $appId): void
    {
        $this->appId = $appId;
    }

    public function getApp(): ?AppEntity
    {
        return $this->app;
    }

    public function setApp(?AppEntity $app): void
    {
        $this->app = $app;
    }
}
