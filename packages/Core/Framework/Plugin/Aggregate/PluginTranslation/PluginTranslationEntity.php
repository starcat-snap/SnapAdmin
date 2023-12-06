<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Aggregate\PluginTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\TranslationEntity;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\PluginEntity;

#[Package('core')]
class PluginTranslationEntity extends TranslationEntity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $pluginId;

    /**
     * @var string|null
     */
    protected $label;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $manufacturerLink;

    /**
     * @var string|null
     */
    protected $supportLink;

    /**
     * @var PluginEntity|null
     */
    protected $plugin;

    public function getPluginId(): string
    {
        return $this->pluginId;
    }

    public function setPluginId(string $pluginId): void
    {
        $this->pluginId = $pluginId;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getManufacturerLink(): ?string
    {
        return $this->manufacturerLink;
    }

    public function setManufacturerLink(string $manufacturerLink): void
    {
        $this->manufacturerLink = $manufacturerLink;
    }

    public function getSupportLink(): ?string
    {
        return $this->supportLink;
    }

    public function setSupportLink(string $supportLink): void
    {
        $this->supportLink = $supportLink;
    }

    public function getPlugin(): ?PluginEntity
    {
        return $this->plugin;
    }

    public function setPlugin(PluginEntity $plugin): void
    {
        $this->plugin = $plugin;
    }
}
