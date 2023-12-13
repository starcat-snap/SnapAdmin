<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework;

use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\Api\Context\ContextSource;
use SnapAdmin\Core\Framework\Api\Context\SystemSource;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\StateAwareTrait;
use SnapAdmin\Core\Framework\Struct\Struct;
use Symfony\Component\Serializer\Annotation\Ignore;

#[Package('core')]
class Context extends Struct
{
    use StateAwareTrait;

    final public const SYSTEM_SCOPE = 'system';
    final public const USER_SCOPE = 'user';
    final public const CRUD_API_SCOPE = 'crud';

    final public const SKIP_TRIGGER_FLOW = 'skipTriggerFlow';

    /**
     * @var non-empty-array<string>
     */
    protected array $languageIdChain;

    protected string $scope = self::USER_SCOPE;

    protected bool $rulesLocked = false;

    #[Ignore]
    protected $extensions = [];

    /**
     * @param array<string> $languageIdChain
     * @param array<string> $ruleIds
     */
    public function __construct(
        protected ContextSource $source,
        protected array $ruleIds = [],
        array $languageIdChain = [Defaults::LANGUAGE_SYSTEM],
        protected string $versionId = Defaults::LIVE_VERSION,
        protected bool $considerInheritance = false
    ) {
        if ($source instanceof SystemSource) {
            $this->scope = self::SYSTEM_SCOPE;
        }

        if (empty($languageIdChain)) {
            throw new \InvalidArgumentException('Argument languageIdChain must not be empty');
        }

        /** @var non-empty-array<string> $chain */
        $chain = array_keys(array_flip(array_filter($languageIdChain)));
        $this->languageIdChain = $chain;
    }

    /**
     * @internal
     */
    public static function createDefaultContext(?ContextSource $source = null): self
    {
        $source ??= new SystemSource();

        return new self($source);
    }

    public function getSource(): ContextSource
    {
        return $this->source;
    }

    public function getVersionId(): string
    {
        return $this->versionId;
    }

    public function getLanguageId(): string
    {
        return $this->languageIdChain[0];
    }

    /**
     * @return array<string>
     */
    public function getRuleIds(): array
    {
        return $this->ruleIds;
    }

    /**
     * @return non-empty-array<string>
     */
    public function getLanguageIdChain(): array
    {
        return $this->languageIdChain;
    }

    public function createWithVersionId(string $versionId): self
    {
        $context = new self(
            $this->source,
            $this->ruleIds,
            $this->languageIdChain,
            $versionId,
            $this->considerInheritance,
        );
        $context->scope = $this->scope;

        foreach ($this->getExtensions() as $key => $extension) {
            $context->addExtension($key, $extension);
        }

        return $context;
    }

    /**
     * @template TReturn of mixed
     *
     * @param callable(Context): TReturn $callback
     *
     * @return TReturn the return value of the provided callback function
     */
    public function scope(string $scope, callable $callback)
    {
        $currentScope = $this->getScope();
        $this->scope = $scope;

        try {
            $result = $callback($this);
        } finally {
            $this->scope = $currentScope;
        }

        return $result;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function considerInheritance(): bool
    {
        return $this->considerInheritance;
    }

    public function setConsiderInheritance(bool $considerInheritance): void
    {
        $this->considerInheritance = $considerInheritance;
    }

    public function isAllowed(string $privilege): bool
    {
        if ($this->source instanceof AdminApiSource) {
            return $this->source->isAllowed($privilege);
        }

        return true;
    }

    /**
     * @template TReturn of mixed
     *
     * @param callable(Context): TReturn $function
     *
     * @return TReturn
     */
    public function enableInheritance(callable $function)
    {
        $previous = $this->considerInheritance;
        $this->considerInheritance = true;
        $result = $function($this);
        $this->considerInheritance = $previous;

        return $result;
    }

    /**
     * @template TReturn of mixed
     *
     * @param callable(Context): TReturn $function
     *
     * @return TReturn
     */
    public function disableInheritance(callable $function)
    {
        $previous = $this->considerInheritance;
        $this->considerInheritance = false;
        $result = $function($this);
        $this->considerInheritance = $previous;

        return $result;
    }

    public function getApiAlias(): string
    {
        return 'context';
    }

    public function lockRules(): void
    {
        $this->rulesLocked = true;
    }
}
