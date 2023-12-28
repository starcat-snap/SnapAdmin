<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Exception;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class ExecuteSequenceException extends \Exception
{
    public function __construct(
        private readonly string $flowId,
        private readonly string $sequenceId,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getFlowId(): string
    {
        return $this->flowId;
    }

    public function getSequenceId(): string
    {
        return $this->sequenceId;
    }
}
