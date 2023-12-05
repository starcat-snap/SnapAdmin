<?php declare(strict_types=1);

$bundles = [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    SnapAdmin\Core\Profiling\Profiling::class => ['all' => true],
    SnapAdmin\Core\Framework\Framework::class => ['all' => true],
    SnapAdmin\Core\System\System::class => ['all' => true],
    SnapAdmin\Core\DevOps\DevOps::class => ['all' => true],
    SnapAdmin\Core\Maintenance\Maintenance::class => ['all' => true],
    SnapAdmin\Administration\Administration::class => ['all' => true],
];

return $bundles;
