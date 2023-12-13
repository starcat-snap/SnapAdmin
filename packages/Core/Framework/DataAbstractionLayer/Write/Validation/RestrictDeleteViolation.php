<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Validation;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class RestrictDeleteViolation
{
    /**
     * @param mixed[][] $restrictions
     */
    public function __construct(
        /**
         * Contains an array which indexed by definition class.
         * Each value represents a single restricted identity
         */
        private readonly array $restrictions
    ) {
    }

    public function getRestrictions(): array
    {
        return $this->restrictions;
    }
}
