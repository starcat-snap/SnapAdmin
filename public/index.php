<?php declare(strict_types=1);

use SnapAdmin\Core\DevOps\Environment\EnvironmentHelper;
use SnapAdmin\Core\Framework\Adapter\Kernel\KernelFactory;
use SnapAdmin\Core\Framework\Plugin\KernelPluginLoader\ComposerPluginLoader;

$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require_once __DIR__ . '/../vendor/autoload_runtime.php';

if (!file_exists(__DIR__ . '/../.env') && !file_exists(__DIR__ . '/../.env.dist') && !file_exists(__DIR__ . '/../.env.local.php')) {
    $_SERVER['APP_RUNTIME_OPTIONS']['disable_dotenv'] = true;
}

return function (array $context) {
    $classLoader = require __DIR__ . '/../vendor/autoload.php';

    $appEnv = $context['APP_ENV'] ?? 'dev';
    $debug = (bool)($context['APP_DEBUG'] ?? ($appEnv !== 'prod'));
    $pluginLoader = null;

    if (EnvironmentHelper::getVariable('COMPOSER_PLUGIN_LOADER', false)) {
        $pluginLoader = new ComposerPluginLoader($classLoader, null);
    }
    return KernelFactory::create(
        environment: $appEnv,
        debug: $debug,
        classLoader: $classLoader,
        pluginLoader: $pluginLoader
    );
};
