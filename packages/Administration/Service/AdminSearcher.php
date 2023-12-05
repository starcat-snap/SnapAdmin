<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Service;

use SnapAdmin\Administration\Framework\Search\CriteriaCollection;
use SnapAdmin\Core\Framework\Api\Acl\Role\AclRoleDefinition;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('administration')]
class AdminSearcher
{
    /**
     * @internal
     */
    public function __construct(private readonly DefinitionInstanceRegistry $definitionRegistry)
    {
    }

    public function search(CriteriaCollection $entities, Context $context): array
    {
        $result = [];

        foreach ($entities as $entityName => $criteria) {
            if (!$this->definitionRegistry->has($entityName)) {
                continue;
            }

            if (!$context->isAllowed($entityName . ':' . AclRoleDefinition::PRIVILEGE_READ)) {
                continue;
            }

            $repository = $this->definitionRegistry->getRepository($entityName);
            $collection = $repository->search($criteria, $context);

            $result[$entityName] = [
                'data' => $collection->getEntities(),
                'total' => $collection->getTotal(),
            ];
        }

        return $result;
    }
}
