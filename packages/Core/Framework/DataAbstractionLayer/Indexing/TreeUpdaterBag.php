<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class TreeUpdaterBag
{
    private array $entities = [];

    private array $updated = [];

    public function getEntity(string $id): ?array
    {
        return $this->entities[$id] ?? null;
    }

    public function addEntity(string $id, array $entity): void
    {
        $this->entities[$id] = $entity;
    }

    public function alreadyUpdated(string $id): bool
    {
        return isset($this->updated[$id]);
    }

    public function addUpdated(string $id): void
    {
        $this->updated[$id] = true;
    }
}
