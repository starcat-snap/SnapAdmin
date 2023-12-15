<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\TestCaseBase;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Kernel;

/**
 * @internal
 */
#[Group('skip-paratest')]
class KernelLifecycleManagerTest extends TestCase
{
    public function testARebootIsPossible(): void
    {
        $oldKernel = KernelLifecycleManager::getKernel();
        $oldConnection = Kernel::getConnection();
        $oldContainer = $oldKernel->getContainer();

        KernelLifecycleManager::bootKernel(false);

        $newKernel = KernelLifecycleManager::getKernel();
        $newConnection = Kernel::getConnection();

        static::assertNotSame(spl_object_hash($oldKernel), spl_object_hash($newKernel));
        static::assertNotSame(spl_object_hash($oldConnection), spl_object_hash($newConnection));
        static::assertNotSame(spl_object_hash($oldContainer), spl_object_hash($newKernel->getContainer()));
    }
}
