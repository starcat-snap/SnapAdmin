<?php declare(strict_types=1);

namespace SnapAdmin\Core\Installer;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as HttpKernel;

class InstallerKernel extends HttpKernel
{
    use MicroKernelTrait;
}
