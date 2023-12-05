<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Exception\ApiProtectionException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Exception\RuntimeFieldInCriteriaException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiCriteriaAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Runtime;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class ApiCriteriaValidator
{
    /**
     * @internal
     */
    public function __construct(private readonly DefinitionInstanceRegistry $registry)
    {
    }

    public function validate(string $entity, Criteria $criteria, Context $context): void
    {
        $definition = $this->registry->getByEntityName($entity);

        foreach ($criteria->getAllFields() as $accessor) {
            $fields = EntityDefinitionQueryHelper::getFieldsOfAccessor($definition, $accessor);

            foreach ($fields as $field) {
                if (!$field instanceof Field) {
                    continue;
                }

                if ($field->getFlag(ApiCriteriaAware::class)) {
                    continue;
                }

                $flag = $field->getFlag(ApiAware::class);

                if ($flag === null) {
                    throw new ApiProtectionException($accessor);
                }

                if (!$flag->isSourceAllowed($context->getSource()::class)) {
                    throw new ApiProtectionException($accessor);
                }

                $runtime = $field->getFlag(Runtime::class);

                if ($runtime !== null) {
                    throw new RuntimeFieldInCriteriaException($accessor);
                }
            }
        }
    }
}
