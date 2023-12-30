<?php declare(strict_types=1);

namespace SnapAdmin\Tests\Integration\Core\Framework\DataAbstractionLayer\Field;

use Doctrine\DBAL\ArrayParameters\Exception\MissingNamedParameter;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\DriverException;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\JsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\JsonFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\EntitySearcherInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command\WriteCommandQueue;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityWriter;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityWriterInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteContext;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use SnapAdmin\Core\Framework\Test\DataAbstractionLayer\Field\DataAbstractionLayerFieldTestBehaviour;
use SnapAdmin\Core\Framework\Test\DataAbstractionLayer\Field\TestDefinition\JsonDefinition;
use SnapAdmin\Core\Framework\Test\DataAbstractionLayer\Field\TestDefinition\NestedDefinition;
use SnapAdmin\Core\Framework\Test\TestCaseBase\CacheTestBehaviour;
use SnapAdmin\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\Framework\Validation\WriteConstraintViolationException;
use SnapAdmin\Core\Test\Stub\DataAbstractionLayer\EmptyEntityExistence;

/**
 * @internal
 */
class JsonFieldTest extends TestCase
{
    use CacheTestBehaviour;
    use DataAbstractionLayerFieldTestBehaviour;
    use KernelTestBehaviour;

    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = $this->getContainer()->get(Connection::class);

        $nullableTable = <<<EOF
DROP TABLE IF EXISTS `_test_nullable`;
CREATE TABLE `_test_nullable` (
  `id` varbinary(16) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4,
  `root` longtext CHARACTER SET utf8mb4,
  `created_at` datetime(3) NOT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

EOF;
        $this->connection->executeStatement($nullableTable);
        $this->connection->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->connection->rollBack();
        $this->connection->executeStatement('DROP TABLE `_test_nullable`');
    }

    public function testSearchForNullFields(): void
    {
        $context = $this->createWriteContext();

        $data = [
            ['id' => Uuid::randomHex(), 'data' => null],
            ['id' => Uuid::randomHex(), 'data' => []],
            ['id' => Uuid::randomHex(), 'data' => ['url' => 'foo']],
        ];

        $this->getWriter()->insert($this->registerDefinition(JsonDefinition::class), $data, $context);

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('_test_nullable.data', null));
        $result = $this->getRepository()->search($this->registerDefinition(JsonDefinition::class), $criteria, $context->getContext());
        static::assertSame(1, $result->getTotal());

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('_test_nullable.data', '[]'));
        $result = $this->getRepository()->search($this->registerDefinition(JsonDefinition::class), $criteria, $context->getContext());
        static::assertSame(1, $result->getTotal());

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('_test_nullable.data.url', 'foo'));
        $result = $this->getRepository()->search($this->registerDefinition(JsonDefinition::class), $criteria, $context->getContext());
        static::assertSame(1, $result->getTotal());
    }

    public function testNullableJsonField(): void
    {
        $id = Uuid::randomHex();
        $context = $this->createWriteContext();

        $data = [
            'id' => $id,
            'data' => null,
        ];

        $this->getWriter()->insert($this->registerDefinition(JsonDefinition::class), [$data], $context);

        $data = $this->connection->fetchAllAssociative('SELECT * FROM `_test_nullable`');

        static::assertCount(1, $data);
        static::assertSame(Uuid::fromHexToBytes($id), $data[0]['id']);
        static::assertNull($data[0]['data']);
    }

    public function testFieldNesting(): void
    {
        $id = Uuid::randomHex();
        $context = $this->createWriteContext();

        $data = [
            'id' => $id,
            'data' => [
                'net' => 15,
                'foo' => [
                    'bar' => false,
                    'baz' => [
                        'deep' => 'invalid',
                    ],
                ],
            ],
        ];

        $ex = null;

        try {
            $this->getWriter()->insert($this->registerDefinition(NestedDefinition::class), [$data], $context);
        } catch (WriteException $ex) {
        }

        static::assertInstanceOf(WriteException::class, $ex);
        static::assertCount(3, $ex->getExceptions());

        $fieldExceptionOne = $ex->getExceptions()[0];
        static::assertInstanceOf(WriteConstraintViolationException::class, $fieldExceptionOne);
        static::assertSame('/0/data', $fieldExceptionOne->getPath());
        static::assertSame('/gross', $fieldExceptionOne->getViolations()->get(0)->getPropertyPath());

        $fieldExceptionTwo = $ex->getExceptions()[1];
        static::assertInstanceOf(WriteConstraintViolationException::class, $fieldExceptionTwo);
        static::assertSame('/0/data/foo', $fieldExceptionTwo->getPath());
        static::assertSame('/bar', $fieldExceptionTwo->getViolations()->get(0)->getPropertyPath());

        $fieldExceptionThree = $ex->getExceptions()[2];
        static::assertInstanceOf(WriteConstraintViolationException::class, $fieldExceptionThree);
        static::assertSame('/0/data/foo/baz', $fieldExceptionThree->getPath());
        static::assertSame('/deep', $fieldExceptionThree->getViolations()->get(0)->getPropertyPath());
    }

    public function testWriteUtf8(): void
    {
        $context = $this->createWriteContext();

        $data = [
            ['id' => Uuid::randomHex(), 'data' => ['a' => '😄']],
        ];

        $written = $this->getWriter()->insert($this->registerDefinition(JsonDefinition::class), $data, $context);

        static::assertArrayHasKey(JsonDefinition::ENTITY_NAME, $written);
        static::assertCount(1, $written[JsonDefinition::ENTITY_NAME]);
        $payload = $written[JsonDefinition::ENTITY_NAME][0]->getPayload();

        static::assertArrayHasKey('data', $payload);
        static::assertArrayHasKey('a', $payload['data']);
        static::assertSame('😄', $payload['data']['a']);
    }

    public function testSqlInjectionFails(): void
    {
        $context = $this->createWriteContext();
        $randomKey = Uuid::randomHex();

        $data = [
            ['id' => Uuid::randomHex(), 'data' => [$randomKey => 'bar']],
        ];
        $written = $this->getWriter()->insert($this->registerDefinition(JsonDefinition::class), $data, $context);
        static::assertCount(1, $written[JsonDefinition::ENTITY_NAME]);

        $context = $context->getContext();

        $taxId = Uuid::randomHex();
        $taxRate = 15.0;

        $repo = $this->getRepository();
        $criteria = new Criteria();

        $connection = $this->getContainer()->get(Connection::class);
        $insertInjection = sprintf(
            'INSERT INTO `tax` (id, tax_rate, name, created_at) VALUES(UNHEX(%s), %s, "foo", %s)',
            (string) $connection->quote($taxId),
            (string) $taxRate, // use php string conversion, to avoid locale based float to string conversion in sprintf
            (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT)
        );
        $keyWithQuotes = sprintf(
            'data.%s\')) = "%s"); %s; SELECT 1 FROM ((("',
            $randomKey,
            'bar',
            $insertInjection
        );

        $criteria->addFilter(new EqualsFilter($keyWithQuotes, 'bar'));

        // invalid json path
        try {
            $repo->search($this->registerDefinition(JsonDefinition::class), $criteria, $context);
        } catch (\Throwable $e) {
            if ($e instanceof DriverException) {
                static::assertStringContainsString('Invalid JSON path expression', $e->getMessage());
            }
            if ($e instanceof MissingNamedParameter) {
                static::assertStringContainsString('Named parameter ', $e->getMessage());
            }
        }
    }

    public function testNestedJsonField(): void
    {
        $insertTime = new \DateTime('2004-02-29 08:59:59.001');

        $serializer = $this->getContainer()->get(JsonFieldSerializer::class);

        $field = new JsonField('root', 'root', [
            new JsonField('child', 'child', [
                new DateTimeField('childDateTime', 'childDateTime'),
                new DateField('childDate', 'childDate'),
            ]),
        ]);

        $value = [
            'child' => [
                'childDateTime' => $insertTime,
                'childDate' => $insertTime,
            ],
        ];

        $payload = $serializer->encode(
            $field,
            new EmptyEntityExistence(),
            new KeyValuePair('root', $value, true),
            new WriteParameterBag(
                $this->createMock(EntityDefinition::class),
                WriteContext::createFromContext(Context::createDefaultContext()),
                '',
                new WriteCommandQueue()
            )
        );

        // assert is generator
        static::assertIsIterable($payload);

        $payload = iterator_to_array($payload);

        static::assertArrayHasKey('root', $payload);
        static::assertIsString($payload['root']);

        $decoded = json_decode($payload['root'], true, 512, \JSON_THROW_ON_ERROR);
        static::assertArrayHasKey('child', $decoded);
        static::assertArrayHasKey('childDateTime', $decoded['child']);

        static::assertEquals($insertTime->format(Defaults::STORAGE_DATE_TIME_FORMAT), $decoded['child']['childDateTime']);
        static::assertEquals($insertTime->format(Defaults::STORAGE_DATE_FORMAT), $decoded['child']['childDate']);
    }

    protected function createWriteContext(): WriteContext
    {
        return WriteContext::createFromContext(Context::createDefaultContext());
    }

    private function getWriter(): EntityWriterInterface
    {
        return $this->getContainer()->get(EntityWriter::class);
    }

    private function getRepository(): EntitySearcherInterface
    {
        return $this->getContainer()->get(EntitySearcherInterface::class);
    }
}
