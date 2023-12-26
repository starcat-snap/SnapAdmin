<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Rule\Container;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\Rule;

#[Package('services-settings')]
interface ContainerInterface
{
    /**
     * @param Rule[] $rules
     */
    public function setRules(array $rules): void;

    public function addRule(Rule $rule): void;
}
