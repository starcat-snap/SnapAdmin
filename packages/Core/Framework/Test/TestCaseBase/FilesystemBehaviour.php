<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\TestCaseBase;

use League\Flysystem\Filesystem;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use SnapAdmin\Core\Framework\Test\Filesystem\Adapter\MemoryAdapterFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Use this trait if your test operates with a filesystem
 */
trait FilesystemBehaviour
{
    public function getFilesystem(string $serviceId): Filesystem
    {
        /** @var Filesystem $filesystem */
        $filesystem = $this->getContainer()->get($serviceId);

        return $filesystem;
    }

    public function getPublicFilesystem(): Filesystem
    {
        return $this->getFilesystem('snap.filesystem.public');
    }

    public function getPrivateFilesystem(): Filesystem
    {
        return $this->getFilesystem('snap.filesystem.private');
    }

    #[Before]
    #[After]
    public function removeWrittenFilesAfterFilesystemTests(): void
    {
        MemoryAdapterFactory::clearInstancesMemory();
    }

    abstract protected static function getContainer(): ContainerInterface;
}
