<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Plugin;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Plugin\BundleConfigGenerator;
use SnapAdmin\Core\Framework\Plugin\BundleConfigGeneratorInterface;
use SnapAdmin\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use SnapAdmin\Frontend\Theme\FrontendPluginRegistry;
use SnapAdmin\Tests\Integration\Core\Framework\App\AppSystemTestBehaviour;

/**
 * @internal
 */
class BundleConfigGeneratorTest extends TestCase
{
    use AppSystemTestBehaviour;
    use IntegrationTestBehaviour;

    private BundleConfigGeneratorInterface $configGenerator;

    protected function setUp(): void
    {
        $this->configGenerator = $this->getContainer()->get(BundleConfigGenerator::class);
    }

    public function testGenerateAppConfigWithThemeAndScriptAndStylePaths(): void
    {
        $appPath = __DIR__ . '/_fixture/apps/theme/';
        $this->loadAppsFromDir($appPath);
        $projectDir = $this->getContainer()->getParameter('kernel.project_dir');

        if (mb_strpos($appPath, $projectDir) === 0) {
            // make relative
            $appPath = ltrim(mb_substr($appPath, mb_strlen($projectDir)), '/');
        }

        $configs = $this->configGenerator->getConfig();

        static::assertArrayHasKey('SwagApp', $configs);

        $appConfig = $configs['SwagApp'];
        static::assertEquals(
            $appPath,
            $appConfig['basePath']
        );
        static::assertEquals(['Resources/views'], $appConfig['views']);
        static::assertEquals('swag-app', $appConfig['technicalName']);
        static::assertArrayNotHasKey('administration', $appConfig);

        static::assertArrayHasKey('frontend', $appConfig);
        $frontendConfig = $appConfig['frontend'];

        static::assertEquals('Resources/app/frontend/src', $frontendConfig['path']);
        static::assertEquals('Resources/app/frontend/src/main.js', $frontendConfig['entryFilePath']);
        static::assertNull($frontendConfig['webpack']);

        // Style files can and need only be imported if frontend is installed
        if ($this->getContainer()->has(FrontendPluginRegistry::class)) {
            $expectedStyles = [
                $appPath . 'Resources/app/frontend/src/scss/base.scss',
                $appPath . 'Resources/app/frontend/src/scss/overrides.scss',
            ];

            static::assertEquals([], array_diff($expectedStyles, $frontendConfig['styleFiles']));
        }
    }

    public function testGenerateAppConfigWithPluginAndScriptAndStylePaths(): void
    {
        $appPath = __DIR__ . '/_fixture/apps/plugin/';
        $this->loadAppsFromDir($appPath);

        $configs = $this->configGenerator->getConfig();
        $projectDir = $this->getContainer()->getParameter('kernel.project_dir');

        static::assertArrayHasKey('SwagApp', $configs);

        $appConfig = $configs['SwagApp'];
        static::assertEquals(
            $appPath,
            $projectDir . '/' . $appConfig['basePath']
        );
        static::assertEquals(['Resources/views'], $appConfig['views']);
        static::assertEquals('swag-app', $appConfig['technicalName']);
        static::assertArrayNotHasKey('administration', $appConfig);

        static::assertArrayHasKey('frontend', $appConfig);
        $frontendConfig = $appConfig['frontend'];

        static::assertEquals('Resources/app/frontend/src', $frontendConfig['path']);
        static::assertEquals('Resources/app/frontend/src/main.js', $frontendConfig['entryFilePath']);
        static::assertNull($frontendConfig['webpack']);

        // Style files can and need only be imported if frontend is installed
        if ($this->getContainer()->has(FrontendPluginRegistry::class)) {
            if (mb_strpos($appPath, $projectDir) === 0) {
                // make relative
                $appPath = ltrim(mb_substr($appPath, mb_strlen($projectDir)), '/');
            }

            // Only base.scss from /_fixture/apps/plugin/ should be included
            $expectedStyles = [
                $appPath . 'Resources/app/frontend/src/scss/base.scss',
            ];

            static::assertEquals($expectedStyles, $frontendConfig['styleFiles']);
        }
    }

    public function testGenerateAppConfigIgnoresInactiveApps(): void
    {
        $appPath = __DIR__ . '/_fixture/apps/theme/';
        $this->loadAppsFromDir($appPath, false);

        $configs = $this->configGenerator->getConfig();

        static::assertArrayNotHasKey('SwagApp', $configs);
    }

    public function testGenerateAppConfigWithWebpackConfig(): void
    {
        $appPath = __DIR__ . '/_fixture/apps/with-webpack/';
        $this->loadAppsFromDir($appPath);

        $configs = $this->configGenerator->getConfig();

        static::assertArrayHasKey('SwagTest', $configs);

        $appConfig = $configs['SwagTest'];
        static::assertEquals(
            $appPath,
            $this->getContainer()->getParameter('kernel.project_dir') . '/' . $appConfig['basePath']
        );
        static::assertEquals(['Resources/views'], $appConfig['views']);
        static::assertEquals('swag-test', $appConfig['technicalName']);
        static::assertArrayNotHasKey('administration', $appConfig);

        static::assertArrayHasKey('frontend', $appConfig);
        $frontendConfig = $appConfig['frontend'];

        static::assertEquals('Resources/app/frontend/src', $frontendConfig['path']);
        static::assertNull($frontendConfig['entryFilePath']);
        static::assertEquals('Resources/app/frontend/build/webpack.config.js', $frontendConfig['webpack']);
        static::assertEquals([], $frontendConfig['styleFiles']);
    }
}
