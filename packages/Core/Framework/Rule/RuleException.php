<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Rule;

use SnapAdmin\Core\Framework\HttpException;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Script\Exception\ScriptExecutionFailedException;
use SnapAdmin\Core\Framework\Script\ScriptException;

#[Package('services-settings')]
class RuleException extends HttpException
{
    public static function scriptExecutionFailed(string $hook, string $scriptName, \Throwable $previous): ScriptException
    {
        // use own exception class so it can be catched properly
        return new ScriptExecutionFailedException($hook, $scriptName, $previous);
    }
}
