<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Kernel;

use Composer\Autoload\ClassLoader;
use SnapAdmin\Core\Kernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class KernelFactory
{
    /**
     * @var class-string<Kernel>
     */
    public static string $kernelClass = Kernel::class;

    public static function create(
        string      $environment,
        bool        $debug,
        ClassLoader $classLoader,
    ): HttpKernelInterface
    {
        /** @var KernelInterface $kernel */
        $kernel = new static::$kernelClass(
            $environment,
            $debug,
        );

        return $kernel;
    }
}
