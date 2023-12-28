<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Rule;

use SnapAdmin\Core\Content\Flow\Aggregate\FlowSequence\FlowSequenceCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\Rule;
use SnapAdmin\Core\Content\Rule\PaymentMethodCollection;
use SnapAdmin\Core\Content\Rule\ProductPriceCollection;
use SnapAdmin\Core\Content\Rule\RuleConditionCollection;
use SnapAdmin\Core\Content\Rule\ShippingMethodCollection;

#[Package('services-settings')]
class RuleEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @internal
     *
     * @var string|Rule|null
     */
    protected $payload;

    /**
     * @var string[]|null
     */
    protected $moduleTypes;

    /**
     * @var ProductPriceCollection|null
     */
    protected $productPrices;

    /**
     * @var ShippingMethodCollection|null
     */
    protected $shippingMethods;

    /**
     * @var PaymentMethodCollection|null
     */
    protected $paymentMethods;

    /**
     * @var RuleConditionCollection|null
     */
    protected $conditions;

    /**
     * @var bool
     */
    protected $invalid;

    /**
     * @var string[]|null
     */
    protected ?array $areas = null;

    /**
     * @var FlowSequenceCollection|null
     */
    protected $flowSequences;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Rule|string|null
     */
    public function getPayload()
    {
        $this->checkIfPropertyAccessIsAllowed('payload');

        return $this->payload;
    }

    /**
     * @internal
     *
     * @param Rule|string|null $payload
     */
    public function setPayload($payload): void
    {
        $this->payload = $payload;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function isInvalid(): bool
    {
        return $this->invalid;
    }

    public function setInvalid(bool $invalid): void
    {
        $this->invalid = $invalid;
    }

    /**
     * @return string[]|null
     */
    public function getAreas(): ?array
    {
        return $this->areas;
    }

    /**
     * @param string[] $areas
     */
    public function setAreas(array $areas): void
    {
        $this->areas = $areas;
    }

    /**
     * @return string[]|null
     */
    public function getModuleTypes(): ?array
    {
        return $this->moduleTypes;
    }

    /**
     * @param string[]|null $moduleTypes
     */
    public function setModuleTypes(?array $moduleTypes): void
    {
        $this->moduleTypes = $moduleTypes;
    }

    public function getFlowSequences(): ?FlowSequenceCollection
    {
        return $this->flowSequences;
    }

    public function setFlowSequences(FlowSequenceCollection $flowSequences): void
    {
        $this->flowSequences = $flowSequences;
    }
}
