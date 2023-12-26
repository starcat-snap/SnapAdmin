<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Script\Execution\Hook;

#[Package('core')]
class HookInjectionException extends \RuntimeException
{
    public function __construct(
        Hook $hook,
        string $class,
        string $required
    ) {
        parent::__construct(sprintf(
            'Class %s is only executable in combination with hooks that implement the %s interface. Hook %s does not implement this interface',
            $class,
            $required,
            $hook->getName()
        ));
    }
}
