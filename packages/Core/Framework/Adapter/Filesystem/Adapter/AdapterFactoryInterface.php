<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Filesystem\Adapter;

use League\Flysystem\FilesystemAdapter;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
interface AdapterFactoryInterface
{
    public function create(array $config): FilesystemAdapter;

    public function getType(): string;
}
