<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Increment;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Increment\AbstractIncrementer;
use SnapAdmin\Core\Framework\Increment\ArrayIncrementer;
use SnapAdmin\Core\Framework\Increment\IncrementerGatewayCompilerPass;
use SnapAdmin\Core\Framework\Increment\MySQLIncrementer;
use SnapAdmin\Core\Framework\Increment\RedisIncrementer;
use SnapAdmin\Core\Framework\Plugin\Exception\DecorationPatternException;
use SnapAdmin\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 */
class IncrementerGatewayCompilerPassTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function testProcess(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('snap.increment', [
            'user_activity' => [
                'type' => 'mysql',
            ],
            'message_queue' => [
                'type' => 'redis',
                'config' => ['url' => 'redis://test'],
            ],
            'another_pool' => [
                'type' => 'array',
            ],
        ]);

        $container->register('snap.increment.gateway.array', ArrayIncrementer::class)
            ->addArgument('');

        $container->register('snap.increment.gateway.mysql', MySQLIncrementer::class)
            ->addArgument('')
            ->addArgument($this->getContainer()->get(Connection::class));

        $entityCompilerPass = new IncrementerGatewayCompilerPass();
        $entityCompilerPass->process($container);

        $definition = $container->getDefinition('snap.increment.user_activity.gateway.mysql');
        static::assertEquals(MySQLIncrementer::class, $definition->getClass());
        static::assertTrue($definition->hasTag('snap.increment.gateway'));

        // message_queue pool is registered
        static::assertEquals(MySQLIncrementer::class, $definition->getClass());
        static::assertTrue($definition->hasTag('snap.increment.gateway'));

        // another_pool is registered
        $definition = $container->getDefinition('snap.increment.message_queue.gateway.redis');
        static::assertEquals(RedisIncrementer::class, $definition->getClass());
        static::assertTrue($definition->hasTag('snap.increment.gateway'));
    }

    public function testCustomPoolGateway(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('snap.increment', ['custom_pool' => ['type' => 'custom_type']]);

        $customGateway = new class() extends AbstractIncrementer {
            public function getDecorated(): AbstractIncrementer
            {
                throw new DecorationPatternException(static::class);
            }

            public function decrement(string $cluster, string $key): void
            {
            }

            public function increment(string $cluster, string $key): void
            {
            }

            /**
             * @return array<string, array<string, mixed>>
             */
            public function list(string $cluster, int $limit = 5, int $offset = 0): array
            {
                return [];
            }

            public function reset(string $cluster, ?string $key = null): void
            {
            }

            public function getPool(): string
            {
                return 'custom-pool';
            }
        };

        $container->setDefinition('snap.increment.custom_pool.gateway.custom_type', new Definition($customGateway::class));

        $entityCompilerPass = new IncrementerGatewayCompilerPass();
        $entityCompilerPass->process($container);

        // custom_pool pool is registered
        $definition = $container->getDefinition('snap.increment.custom_pool.gateway.custom_type');
        static::assertEquals($customGateway::class, $definition->getClass());
        static::assertTrue($definition->hasTag('snap.increment.gateway'));
    }

    public function testInvalidCustomPoolGateway(): void
    {
        static::expectException(\RuntimeException::class);
        $container = new ContainerBuilder();
        $container->setParameter('snap.increment', ['custom_pool' => []]);
        $container->setParameter('snap.increment.custom_pool.type', 'custom_type');

        $customGateway = new class() {
            public function getPool(): string
            {
                return 'custom-pool';
            }
        };

        $container->setDefinition('snap.increment.custom_pool.gateway.custom_type', new Definition($customGateway::class));

        $entityCompilerPass = new IncrementerGatewayCompilerPass();
        $entityCompilerPass->process($container);

        $definition = $container->getDefinition('snap.increment.custom_pool.gateway.custom_type');
        static::assertEquals($customGateway::class, $definition->getClass());
        static::assertTrue($definition->hasTag('snap.increment.gateway'));
    }

    public function testInvalidType(): void
    {
        static::expectException(\RuntimeException::class);
        static::expectExceptionMessage('Can not find increment gateway for configured type foo of pool custom_pool, expected service id snap.increment.custom_pool.gateway.foo can not be found');
        $container = new ContainerBuilder();
        $container->setParameter('snap.increment', ['custom_pool' => [
            'type' => 'foo',
        ]]);
        $container->setParameter('snap.increment.custom_pool.type', 'invalid');

        $entityCompilerPass = new IncrementerGatewayCompilerPass();
        $entityCompilerPass->process($container);
    }

    public function testInvalidAdapterClass(): void
    {
        static::expectException(\RuntimeException::class);
        static::expectExceptionMessage('Increment gateway with id snap.increment.custom_pool.gateway.array, expected service instance of SnapAdmin\Core\Framework\Increment\AbstractIncrementer');
        $container = new ContainerBuilder();
        $container->setParameter('snap.increment', ['custom_pool' => ['type' => 'array']]);
        $container->setParameter('snap.increment.custom_pool.type', 'custom_type');
        $container->setDefinition('snap.increment.gateway.array', new Definition(\ArrayObject::class));

        $entityCompilerPass = new IncrementerGatewayCompilerPass();
        $entityCompilerPass->process($container);
    }

    public function testInvalidRedisAdapter(): void
    {
        static::expectException(\RuntimeException::class);
        static::expectExceptionMessage('Can not find increment gateway for configured type redis of pool custom_pool, expected service id snap.increment.custom_pool.gateway.redis can not be found');

        $container = new ContainerBuilder();
        $container->setParameter('snap.increment', ['custom_pool' => ['type' => 'redis']]);
        $container->setParameter('snap.increment.custom_pool.type', 'custom_type');

        $entityCompilerPass = new IncrementerGatewayCompilerPass();
        $entityCompilerPass->process($container);
    }
}
