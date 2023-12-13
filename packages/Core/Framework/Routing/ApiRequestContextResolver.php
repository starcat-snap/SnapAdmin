<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\Api\Context\ContextSource;
use SnapAdmin\Core\Framework\Api\Context\SystemSource;
use SnapAdmin\Core\Framework\Api\Util\AccessKeyHelper;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\PlatformRequest;
use Symfony\Component\HttpFoundation\Request;

#[Package('core')]
class ApiRequestContextResolver implements RequestContextResolverInterface
{
    use RouteScopeCheckTrait;

    /**
     * @internal
     */
    public function __construct(
        private readonly Connection         $connection,
        private readonly RouteScopeRegistry $routeScopeRegistry
    )
    {
    }

    public function resolve(Request $request): void
    {
        if ($request->attributes->has(PlatformRequest::ATTRIBUTE_CONTEXT_OBJECT)) {
            return;
        }

        if (!$this->isRequestScoped($request, ApiContextRouteScopeDependant::class)) {
            return;
        }

        $params = $this->getContextParameters($request);
        $languageIdChain = $this->getLanguageIdChain($params);


        $context = new Context(
            $this->resolveContextSource($request),
            [],
            $languageIdChain,
            $params['versionId'] ?? Defaults::LIVE_VERSION,
            $params['considerInheritance'],
        );

        if ($request->headers->has(PlatformRequest::HEADER_SKIP_TRIGGER_FLOW)) {
            $skipTriggerFlow = filter_var($request->headers->get(PlatformRequest::HEADER_SKIP_TRIGGER_FLOW, 'false'), \FILTER_VALIDATE_BOOLEAN);

            if ($skipTriggerFlow) {
                $context->addState(Context::SKIP_TRIGGER_FLOW);
            }
        }

        $request->attributes->set(PlatformRequest::ATTRIBUTE_CONTEXT_OBJECT, $context);
    }

    protected function getScopeRegistry(): RouteScopeRegistry
    {
        return $this->routeScopeRegistry;
    }

    /**
     * @return array{languageId: string, systemFallbackLanguageId: string, versionId: ?string, considerInheritance: bool}
     */
    private function getContextParameters(Request $request): array
    {
        $params = [
            'languageId' => Defaults::LANGUAGE_SYSTEM,
            'systemFallbackLanguageId' => Defaults::LANGUAGE_SYSTEM,
            'versionId' => $request->headers->get(PlatformRequest::HEADER_VERSION_ID),
            'considerInheritance' => false,
        ];

        $runtimeParams = $this->getRuntimeParameters($request);

        /** @var array{languageId: string, systemFallbackLanguageId: string, versionId: ?string, considerInheritance: bool} $params */
        $params = array_replace_recursive($params, $runtimeParams);

        return $params;
    }

    /**
     * @return array{languageId?: string, considerInheritance?: true}
     */
    private function getRuntimeParameters(Request $request): array
    {
        $parameters = [];

        if ($request->headers->has(PlatformRequest::HEADER_LANGUAGE_ID)) {
            $langHeader = $request->headers->get(PlatformRequest::HEADER_LANGUAGE_ID);

            if ($langHeader !== null) {
                $parameters['languageId'] = $langHeader;
            }
        }

        if ($request->headers->has(PlatformRequest::HEADER_INHERITANCE)) {
            $parameters['considerInheritance'] = true;
        }

        return $parameters;
    }

    private function resolveContextSource(Request $request): ContextSource
    {
        if ($userId = $request->attributes->get(PlatformRequest::ATTRIBUTE_OAUTH_USER_ID)) {
            return $this->getAdminApiSource($userId);
        }

        if (!$request->attributes->has(PlatformRequest::ATTRIBUTE_OAUTH_ACCESS_TOKEN_ID)) {
            return new SystemSource();
        }

        $clientId = $request->attributes->get(PlatformRequest::ATTRIBUTE_OAUTH_CLIENT_ID);
        $keyOrigin = AccessKeyHelper::getOrigin($clientId);

        if ($keyOrigin === 'user') {
            $userId = $this->getUserIdByAccessKey($clientId);

            return $this->getAdminApiSource($userId);
        }

        return new SystemSource();
    }

    /**
     * @param array{languageId: string, systemFallbackLanguageId: string} $params
     *
     * @return non-empty-array<string>
     */
    private function getLanguageIdChain(array $params): array
    {
        $chain = [$params['languageId']];
        if ($chain[0] === Defaults::LANGUAGE_SYSTEM) {
            return $chain; // no query needed
        }
        // `Context` ignores nulls and duplicates
        $chain[] = $this->getParentLanguageId($chain[0]);
        $chain[] = $params['systemFallbackLanguageId'];

        /** @var non-empty-array<string> $filtered */
        $filtered = array_filter($chain);

        return $filtered;
    }

    private function getParentLanguageId(?string $languageId): ?string
    {
        if ($languageId === null || !Uuid::isValid($languageId)) {
            throw RoutingException::languageNotFound($languageId);
        }
        $data = $this->connection->createQueryBuilder()
            ->select(['LOWER(HEX(language.parent_id))'])
            ->from('language')
            ->where('language.id = :id')
            ->setParameter('id', Uuid::fromHexToBytes($languageId))
            ->executeQuery()
            ->fetchFirstColumn();

        if (empty($data)) {
            throw RoutingException::languageNotFound($languageId);
        }

        return $data[0];
    }

    private function getUserIdByAccessKey(string $clientId): string
    {
        $id = $this->connection->createQueryBuilder()
            ->select(['user_id'])
            ->from('user_access_key')
            ->where('access_key = :accessKey')
            ->setParameter('accessKey', $clientId)
            ->executeQuery()
            ->fetchOne();

        return Uuid::fromBytesToHex($id);
    }

    private function getAdminApiSource(string $userId): AdminApiSource
    {
        $source = new AdminApiSource($userId);
        if ($userId != null) {
            $source->setPermissions($this->fetchPermissions($userId));
            $source->setIsAdmin($this->isAdmin($userId));

            return $source;
        }
        return $source;
    }

    private function isAdmin(string $userId): bool
    {
        return (bool)$this->connection->fetchOne(
            'SELECT admin FROM `user` WHERE id = :id',
            ['id' => Uuid::fromHexToBytes($userId)]
        );
    }

    /**
     * @return string[]
     */
    private function fetchPermissions(string $userId): array
    {
        $permissions = $this->connection->createQueryBuilder()
            ->select(['role.privileges'])
            ->from('acl_user_role', 'mapping')
            ->innerJoin('mapping', 'acl_role', 'role', 'mapping.acl_role_id = role.id')
            ->where('mapping.user_id = :userId')
            ->setParameter('userId', Uuid::fromHexToBytes($userId))
            ->executeQuery()
            ->fetchFirstColumn();

        $list = [];
        foreach ($permissions as $privileges) {
            $privileges = json_decode((string)$privileges, true, 512, \JSON_THROW_ON_ERROR);
            $list = array_merge($list, $privileges);
        }

        return array_unique(array_filter($list));
    }
}
