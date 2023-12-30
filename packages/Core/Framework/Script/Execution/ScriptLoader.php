<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script\Execution;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\DevOps\Environment\EnvironmentHelper;
use SnapAdmin\Core\Framework\Adapter\Cache\CacheCompressor;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Cache\FilesystemCache;

/**
 * @internal only for use by the app-system
 *
 * @phpstan-type ScriptInfo = array{scriptName: string, script: string, hook: string,  lastModified: string, appVersion: string, active: bool}
 * @phpstan-type IncludesInfo = array{name: string, script: string,lastModified: string}
 */
#[Package('core')]
class ScriptLoader implements EventSubscriberInterface
{
    final public const CACHE_KEY = 'snap-admin-scripts';

    private readonly string $cacheDir;

    public function __construct(
        private readonly Connection               $connection,
        private readonly TagAwareAdapterInterface $cache,
        string                                    $cacheDir,
        private readonly bool                     $debug
    )
    {
        $this->cacheDir = $cacheDir . '/scripts';
    }

    public static function getSubscribedEvents(): array
    {
        return ['script.written' => 'invalidateCache'];
    }

    /**
     * @return Script[]
     */
    public function get(string $hook): array
    {
        $cacheItem = $this->cache->getItem(self::CACHE_KEY);
        if ($cacheItem->isHit() && $cacheItem->get() && !$this->debug) {
            return CacheCompressor::uncompress($cacheItem)[$hook] ?? [];
        }

        $scripts = $this->load();

        $cacheItem = CacheCompressor::compress($cacheItem, $scripts);
        $this->cache->save($cacheItem);

        return $scripts[$hook] ?? [];
    }

    public function invalidateCache(): void
    {
        $this->cache->deleteItem(self::CACHE_KEY);
    }

    /**
     * @return array<string, list<Script>>
     */
    private function load(): array
    {
        /** @var list<ScriptInfo> $scripts */
        $scripts = $this->connection->fetchAllAssociative('
            SELECT
                   `script`.`name` AS scriptName,
                   `script`.`script` AS script,
                   `script`.`hook` AS hook,
                   IFNULL(`script`.`updated_at`, `script`.`created_at`) AS lastModified,
                   `script`.`active` AS active
            FROM `script`
            WHERE `script`.`hook` != \'include\'
            ORDER BY `script`.`name`
        ');

        $includes = $this->connection->fetchAllAssociative('
            SELECT
                   `script`.`name` AS name,
                   `script`.`script` AS script,
                   IFNULL(`script`.`updated_at`, `script`.`created_at`) AS lastModified
            FROM `script`
            WHERE `script`.`hook` = \'include\'
            ORDER BY `script`.`name`
        ');

        $executableScripts = [];
        foreach ($scripts as $script) {

            $includes = [];

            $dates = [...[$script['lastModified']], ...array_column($includes, 'lastModified')];

            $lastModified = new \DateTimeImmutable(max($dates));

            $cachePrefix = EnvironmentHelper::getVariable('INSTANCE_ID', '');

            $includes = array_map(function (array $script) {
                return new Script(
                    $script['name'],
                    $script['script'],
                    new \DateTimeImmutable($script['lastModified']),
                );
            }, $includes);

            $options = [];
            if (!$this->debug) {
                $options['cache'] = new FilesystemCache($this->cacheDir . '/' . $cachePrefix);
            } else {
                $options['debug'] = true;
            }

            $executableScripts[$script['hook']][] = new Script(
                $script['scriptName'],
                $script['script'],
                $lastModified,
                $options,
                $includes,
                (bool)$script['active']
            );
        }

        return $executableScripts;
    }
}
