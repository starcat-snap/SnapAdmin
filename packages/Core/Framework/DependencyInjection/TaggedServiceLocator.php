<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DependencyInjection;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\Service\ServiceProviderInterface;

/**
 * @internal
 *
 * @implements ServiceProviderInterface<mixed>
 */
#[Package('core')]
class TaggedServiceLocator implements ServiceProviderInterface
{
    /**
     * @param ServiceProviderInterface<mixed> $inner
     */
    public function __construct(private readonly ServiceProviderInterface $inner)
    {
    }

    public function get(string $id): mixed
    {
        return $this->inner->get($id);
    }

    public function has(string $id): bool
    {
        return $this->inner->has($id);
    }

    public function getProvidedServices(): array
    {
        return $this->inner->getProvidedServices();
    }
}
