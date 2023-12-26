<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script\Api;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Script\Execution\ScriptExecutor;
use SnapAdmin\Core\Framework\Script\Facade\ArrayFacade;
use SnapAdmin\Core\Framework\Script\ScriptException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class ScriptResponse
{
    private ArrayFacade $body;

    private readonly ResponseCacheConfiguration $cache;

    /**
     * @internal
     */
    public function __construct(
        private readonly ?Response $inner = null,
        private int $code = Response::HTTP_OK
    ) {
        $this->body = new ArrayFacade([]);
        $this->cache = new ResponseCacheConfiguration();
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function getBody(): ArrayFacade
    {
        return $this->body;
    }

    /**
     * @param array<mixed>|ArrayFacade<mixed> $body
     */
    public function setBody(array|ArrayFacade $body): void
    {
        if (\is_array($body)) {
            $body = new ArrayFacade($body);
        }

        $this->body = $body;
    }

    public function getCache(): ResponseCacheConfiguration
    {
        return $this->cache;
    }

    /**
     * @internal access from twig scripts is not supported
     */
    public function getInner(): ?Response
    {
        if (ScriptExecutor::$isInScriptExecutionContext) {
            throw ScriptException::accessFromScriptExecutionContextNotAllowed(self::class, __METHOD__);
        }

        return $this->inner;
    }
}
