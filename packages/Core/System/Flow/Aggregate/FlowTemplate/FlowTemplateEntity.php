<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Aggregate\FlowTemplate;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class FlowTemplateEntity extends Entity
{
    use EntityIdTrait;

    protected string $name;

    /**
     * @var array<string, mixed>
     */
    protected array $config;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array<string, mixed> $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }
}
