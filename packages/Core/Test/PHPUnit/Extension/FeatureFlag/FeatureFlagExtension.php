<?php declare(strict_types=1);

namespace SnapAdmin\Core\Test\PHPUnit\Extension\FeatureFlag;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Test\PHPUnit\Extension\FeatureFlag\Subscriber\TestFinishedSubscriber;
use SnapAdmin\Core\Test\PHPUnit\Extension\FeatureFlag\Subscriber\TestPreparationStartedSubscriber;
use SnapAdmin\Core\Test\PHPUnit\Extension\FeatureFlag\Subscriber\TestSkippedSubscriber;

/**
 * This extension guarantees a clean feature environment for pure unit tests
 *
 * @internal
 */
#[Package('core')]
class FeatureFlagExtension implements Extension
{
    public const NAMESPACE_PREFIX = 'SnapAdmin\\Tests\\Unit\\';

    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $savedConfig = new SavedConfig();

        $facade->registerSubscribers(
            new TestPreparationStartedSubscriber($savedConfig),
            new TestFinishedSubscriber($savedConfig),
            new TestSkippedSubscriber($savedConfig)
        );
    }
}
