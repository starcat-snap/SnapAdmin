<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Script\Debugging\ScriptTraces;

/**
 * This class is intended for auto-completion in twig templates. So the developer can
 * set a doc block to get auto-completion for all services.
 *
 * @example: {# @var services \SnapAdmin\Core\Framework\Script\ServiceStubs #}
 *
 * @method \SnapAdmin\Core\Framework\DataAbstractionLayer\Facade\RepositoryFacade repository()
 * @method \SnapAdmin\Core\System\SystemConfig\Facade\SystemConfigFacade config()
 * @method \SnapAdmin\Core\Framework\DataAbstractionLayer\Facade\RepositoryWriterFacade writer()
 * @method \SnapAdmin\Core\Framework\Routing\Facade\RequestFacade request()
 * @method \SnapAdmin\Core\Framework\Script\Api\ScriptResponseFactoryFacade response()
 * @method \SnapAdmin\Core\Framework\Adapter\Cache\Script\Facade\CacheInvalidatorFacade cache()
 */
#[Package('core')]
final class ServiceStubs
{
    private string $hook;

    /**
     * @var array<string, array{deprecation?: string, service: object}>
     */
    private array $services = [];

    /**
     * @internal
     */
    public function __construct(string $hook)
    {
        $this->hook = $hook;
    }

    /**
     * @param array<mixed> $arguments
     *
     * @internal
     *
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): object
    {
        if (!isset($this->services[$name])) {
            throw ScriptException::serviceNotAvailableInHook($name, $this->hook);
        }

        if (isset($this->services[$name]['deprecation'])) {
            ScriptTraces::addDeprecationNotice($this->services[$name]['deprecation']);
        }

        return $this->services[$name]['service'];
    }

    /**
     * @internal
     */
    public function add(string $name, object $service, ?string $deprecationNotice = null): void
    {
        if (isset($this->services[$name])) {
            throw ScriptException::serviceAlreadyExists($name);
        }

        $this->services[$name]['service'] = $service;

        if ($deprecationNotice) {
            $this->services[$name]['deprecation'] = $deprecationNotice;
        }
    }

    /**
     * @internal
     */
    public function get(string $name): object
    {
        if (!isset($this->services[$name])) {
            throw ScriptException::serviceNotAvailableInHook($name, $this->hook);
        }

        if (isset($this->services[$name]['deprecation'])) {
            ScriptTraces::addDeprecationNotice($this->services[$name]['deprecation']);
        }

        return $this->services[$name]['service'];
    }
}
