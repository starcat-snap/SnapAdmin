<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

/**
 * @internal
 */
#[Package('core')]
class TestSessionStorageFactory implements SessionStorageFactoryInterface
{
    public function createStorage(?Request $request): SessionStorageInterface
    {
        return new TestSessionStorage();
    }
}
