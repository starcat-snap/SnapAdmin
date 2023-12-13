<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\RemoteAddressField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 */
#[Package('core')]
class RemoteAddressFieldSerializer extends AbstractFieldSerializer
{
    protected const CONFIG_KEY = 'core.loginRegistration.customerIpAddressesNotAnonymously';

    /**
     * @internal
     */
    public function __construct(
        ValidatorInterface $validator,
        DefinitionInstanceRegistry $definitionRegistry,
        private readonly SystemConfigService $configService
    ) {
        parent::__construct($validator, $definitionRegistry);
    }

    public function encode(
        Field $field,
        EntityExistence $existence,
        KeyValuePair $data,
        WriteParameterBag $parameters
    ): \Generator {
        if (!$field instanceof RemoteAddressField) {
            throw DataAbstractionLayerException::invalidSerializerField(RemoteAddressField::class, $field);
        }

        if (!$data->getValue()) {
            return;
        }

        if ($this->configService->get(self::CONFIG_KEY)) {
            yield $field->getStorageName() => $data->getValue();

            return;
        }

        yield $field->getStorageName() => IPUtils::anonymize($data->getValue());
    }

    public function decode(Field $field, mixed $value): ?string
    {
        return $value;
    }
}
