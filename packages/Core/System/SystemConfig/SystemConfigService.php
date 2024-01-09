<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Bundle;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Doctrine\MultiInsertQueryQueue;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ConfigJsonField;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Util\Json;
use SnapAdmin\Core\Framework\Util\XmlReader;
use SnapAdmin\Core\Framework\Uuid\Exception\InvalidUuidException;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\System\SystemConfig\Event\BeforeSystemConfigChangedEvent;
use SnapAdmin\Core\System\SystemConfig\Event\SystemConfigChangedEvent;
use SnapAdmin\Core\System\SystemConfig\Event\SystemConfigDomainLoadedEvent;
use SnapAdmin\Core\System\SystemConfig\Exception\BundleConfigNotFoundException;
use SnapAdmin\Core\System\SystemConfig\Exception\InvalidDomainException;
use SnapAdmin\Core\System\SystemConfig\Exception\InvalidKeyException;
use SnapAdmin\Core\System\SystemConfig\Exception\InvalidSettingValueException;
use SnapAdmin\Core\System\SystemConfig\Util\ConfigReader;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Service\ResetInterface;

#[Package('system-settings')]
class SystemConfigService implements ResetInterface
{
    /**
     * @var array<string, true>
     */
    private array $keys = ['all' => true];

    /**
     * @var array<string, array<string, true>>
     */
    private array $traces = [];

    /**
     * @internal
     */
    public function __construct(
        private readonly Connection $connection,
        private readonly ConfigReader $configReader,
        private readonly AbstractSystemConfigLoader $loader,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly bool $fineGrainedCache
    ) {
    }

    public static function buildName(string $key): string
    {
        return 'config.' . $key;
    }

    /**
     * @return array<mixed>|bool|float|int|string|null
     */
    public function get(string $key, ?string $scopeId = null)
    {
        if ($this->fineGrainedCache) {
            foreach (array_keys($this->keys) as $trace) {
                $this->traces[$trace][self::buildName($key)] = true;
            }
        } else {
            foreach (array_keys($this->keys) as $trace) {
                $this->traces[$trace]['global.system.config'] = true;
            }
        }

        $config = $this->loader->load($scopeId);

        $parts = explode('.', $key);

        $pointer = $config;

        foreach ($parts as $part) {
            if (!\is_array($pointer)) {
                return null;
            }

            if (\array_key_exists($part, $pointer)) {
                $pointer = $pointer[$part];

                continue;
            }

            return null;
        }

        return $pointer;
    }

    public function getString(string $key, ?string $scopeId = null): string
    {
        $value = $this->get($key, $scopeId);
        if (!\is_array($value)) {
            return (string) $value;
        }

        throw new InvalidSettingValueException($key, 'string', \gettype($value));
    }

    public function getInt(string $key, ?string $scopeId = null): int
    {
        $value = $this->get($key, $scopeId);
        if (!\is_array($value)) {
            return (int) $value;
        }

        throw new InvalidSettingValueException($key, 'int', \gettype($value));
    }

    public function getFloat(string $key, ?string $scopeId = null): float
    {
        $value = $this->get($key, $scopeId);
        if (!\is_array($value)) {
            return (float) $value;
        }

        throw new InvalidSettingValueException($key, 'float', \gettype($value));
    }

    public function getBool(string $key, ?string $scopeId = null): bool
    {
        return (bool) $this->get($key, $scopeId);
    }

    /**
     * @internal should not be used in storefront or store api. The cache layer caches all accessed config keys and use them as cache tag.
     *
     * gets all available shop configs and returns them as an array
     *
     * @return array<mixed>
     */
    public function all(?string $scopeId = null): array
    {
        return $this->loader->load($scopeId);
    }

    /**
     * @internal should not be used in storefront or store api. The cache layer caches all accessed config keys and use them as cache tag.
     *
     * @throws InvalidDomainException
     *
     * @return array<mixed>
     */
    public function getDomain(string $domain, ?string $scopeId = null, bool $inherit = false): array
    {
        $domain = trim($domain);
        if ($domain === '') {
            throw new InvalidDomainException('Empty domain');
        }

        $queryBuilder = $this->connection->createQueryBuilder()
            ->select(['configuration_key', 'configuration_value'])
            ->from('system_config');

        if ($inherit) {
            $queryBuilder->where('scope_id IS NULL OR scope_id = :scopeId');
        } elseif ($scopeId === null) {
            $queryBuilder->where('scope_id IS NULL');
        } else {
            $queryBuilder->where('scope_id = :scopeId');
        }

        $domain = rtrim($domain, '.') . '.';
        $escapedDomain = str_replace('%', '\\%', $domain);

        $scopeId = $scopeId ? Uuid::fromHexToBytes($scopeId) : null;

        $queryBuilder->andWhere('configuration_key LIKE :prefix')
            ->addOrderBy('scope_id', 'ASC')
            ->setParameter('prefix', $escapedDomain . '%')
            ->setParameter('scopeId', $scopeId);

        $configs = $queryBuilder->executeQuery()->fetchAllNumeric();

        if ($configs === []) {
            return [];
        }

        $merged = [];

        foreach ($configs as [$key, $value]) {
            if ($value !== null) {
                $value = \json_decode((string) $value, true, 512, \JSON_THROW_ON_ERROR);

                if ($value === false || !isset($value[ConfigJsonField::STORAGE_KEY])) {
                    $value = null;
                } else {
                    $value = $value[ConfigJsonField::STORAGE_KEY];
                }
            }

            $inheritedValuePresent = \array_key_exists($key, $merged);
            $valueConsideredEmpty = !\is_bool($value) && empty($value);

            if ($inheritedValuePresent && $valueConsideredEmpty) {
                continue;
            }

            $merged[$key] = $value;
        }

        $event = new SystemConfigDomainLoadedEvent($domain, $merged, $inherit, $scopeId);
        $this->eventDispatcher->dispatch($event);

        return $event->getConfig();
    }

    /**
     * @param array<mixed>|bool|float|int|string|null $value
     */
    public function set(string $key, $value, ?string $scopeId = null): void
    {
        $this->setMultiple([$key => $value], $scopeId);
    }

    /**
     * @param array<string, array<mixed>|bool|float|int|string|null> $values
     */
    public function setMultiple(array $values, ?string $scopeId = null): void
    {
        $where = $scopeId ? 'scope_id = :scopeId' : 'scope_id IS NULL';

        $existingIds = $this->connection
            ->fetchAllKeyValue(
                'SELECT configuration_key, id FROM system_config WHERE ' . $where . ' and configuration_key IN (:configurationKeys)',
                [
                    'scopeId' => $scopeId ? Uuid::fromHexToBytes($scopeId) : null,
                    'configurationKeys' => array_keys($values),
                ],
                [
                    'configurationKeys' => ArrayParameterType::STRING,
                ]
            );

        $toBeDeleted = [];
        $insertQueue = new MultiInsertQueryQueue($this->connection, 100, false, true);
        $events = [];

        foreach ($values as $key => $value) {
            $key = trim($key);
            $this->validate($key, $scopeId);

            $event = new BeforeSystemConfigChangedEvent($key, $value, $scopeId);
            $this->eventDispatcher->dispatch($event);

            // Use modified value provided by potential event subscribers.
            $value = $event->getValue();

            // On null value, delete the config
            if ($value === null) {
                $toBeDeleted[] = $key;

                $events[] = new SystemConfigChangedEvent($key, $value, $scopeId);

                continue;
            }

            if (isset($existingIds[$key])) {
                $this->connection->update(
                    'system_config',
                    [
                        'configuration_value' => Json::encode(['_value' => $value]),
                        'updated_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                    ],
                    [
                        'id' => $existingIds[$key],
                    ]
                );

                $events[] = new SystemConfigChangedEvent($key, $value, $scopeId);

                continue;
            }

            $insertQueue->addInsert(
                'system_config',
                [
                    'id' => Uuid::randomBytes(),
                    'configuration_key' => $key,
                    'configuration_value' => Json::encode(['_value' => $value]),
                    'scope_id' => $scopeId ? Uuid::fromHexToBytes($scopeId) : null,
                    'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                ],
            );

            $events[] = new SystemConfigChangedEvent($key, $value, $scopeId);
        }

        // Delete all null values
        if (!empty($toBeDeleted)) {
            $qb = $this->connection
                ->createQueryBuilder()
                ->where('configuration_key IN (:keys)')
                ->setParameter('keys', $toBeDeleted, ArrayParameterType::STRING);

            if ($scopeId) {
                $qb->andWhere('scope_id = :scopeId')
                    ->setParameter('scopeId', Uuid::fromHexToBytes($scopeId));
            } else {
                $qb->andWhere('scope_id IS NULL');
            }

            $qb->delete('system_config')
                ->executeStatement();
        }

        $insertQueue->execute();

        // Dispatch events that the given values have been changed
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }

    public function delete(string $key, ?string $scope = null): void
    {
        $this->setMultiple([$key => null], $scope);
    }

    /**
     * Fetches default values from bundle configuration and saves it to database
     */
    public function savePluginConfiguration(Bundle $bundle, bool $override = false): void
    {
        try {
            $config = $this->configReader->getConfigFromBundle($bundle);
        } catch (BundleConfigNotFoundException) {
            return;
        }

        $prefix = $bundle->getName() . '.config.';

        $this->saveConfig($config, $prefix, $override);
    }

    /**
     * @param array<mixed> $config
     */
    public function saveConfig(array $config, string $prefix, bool $override): void
    {
        $relevantSettings = $this->getDomain($prefix);

        foreach ($config as $card) {
            foreach ($card['elements'] as $element) {
                $key = $prefix . $element['name'];
                if (!isset($element['defaultValue'])) {
                    continue;
                }

                $value = XmlReader::phpize($element['defaultValue']);
                if ($override || !isset($relevantSettings[$key])) {
                    $this->set($key, $value);
                }
            }
        }
    }

    public function deletePluginConfiguration(Bundle $bundle): void
    {
        try {
            $config = $this->configReader->getConfigFromBundle($bundle);
        } catch (BundleConfigNotFoundException) {
            return;
        }

        $this->deleteExtensionConfiguration($bundle->getName(), $config);
    }

    /**
     * @param array<mixed> $config
     */
    public function deleteExtensionConfiguration(string $extensionName, array $config): void
    {
        $prefix = $extensionName . '.config.';

        $configKeys = [];
        foreach ($config as $card) {
            foreach ($card['elements'] as $element) {
                $configKeys[] = $prefix . $element['name'];
            }
        }

        if (empty($configKeys)) {
            return;
        }

        $this->setMultiple(array_fill_keys($configKeys, null));
    }

    /**
     * @template TReturn of mixed
     *
     * @param \Closure(): TReturn $param
     *
     * @return TReturn All kind of data could be cached
     */
    public function trace(string $key, \Closure $param)
    {
        $this->traces[$key] = [];
        $this->keys[$key] = true;

        $result = $param();

        unset($this->keys[$key]);

        return $result;
    }

    /**
     * @return array<string>
     */
    public function getTrace(string $key): array
    {
        $trace = isset($this->traces[$key]) ? array_keys($this->traces[$key]) : [];
        unset($this->traces[$key]);

        return $trace;
    }

    /**
     * @throws InvalidKeyException
     * @throws InvalidUuidException
     */
    private function validate(string $key, ?string $scopeId): void
    {
        $key = trim($key);
        if ($key === '') {
            throw new InvalidKeyException('key may not be empty');
        }
        if ($scopeId && !Uuid::isValid($scopeId)) {
            throw new InvalidUuidException($scopeId);
        }
    }

    public function reset()
    {
        // TODO: Implement reset() method.
    }
}
