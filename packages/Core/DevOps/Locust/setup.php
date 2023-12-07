<?php declare(strict_types=1);

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;

$connection = require __DIR__ . '/boot.php';

$connection->executeStatement('SET sql_mode=(SELECT REPLACE(@@sql_mode,\'ONLY_FULL_GROUP_BY\',\'\'))');

$fs = new Filesystem();

$env = json_decode((string)file_get_contents(__DIR__ . '/env.dist.json'), true, 512, \JSON_THROW_ON_ERROR);
if (file_exists(__DIR__ . '/env.json')) {
    $env = array_replace_recursive($env, json_decode((string)file_get_contents(__DIR__ . '/env.json'), true, 512, \JSON_THROW_ON_ERROR));
}

/** @var Connection $connection */
$limit = $env['category_page_limit'] !== null ? ' LIMIT ' . (int)$env['category_page_limit'] : '';

$channel = $connection->fetchAssociative(
    '
SELECT
        LOWER(HEX(channel.country_id)) as countryId,
        channel.access_key,
        LOWER(HEX(channel.id)) as id,
        channel_domain.url
FROM channel
    INNER JOIN channel_domain ON(channel_domain.channel_id = channel.id)
    INNER JOIN channel_translation ON (channel_translation.channel_id = channel.id AND channel_translation.language_id = 0x2fbb5fe2e29a4d70aa5854ce7ce3e20b)
WHERE JSON_UNQUOTE(JSON_EXTRACT(channel_translation.custom_fields, "$.is_for_benchmark")) = "true"'
);

if (empty($channel)) {
    /** @var array $channel */
    $channel = $connection->fetchAssociative(
        '
        SELECT
            LOWER(HEX(channel.country_id)) as countryId,
            channel.access_key,
            LOWER(HEX(channel.id)) as id,
            channel_domain.url

        FROM channel
            INNER JOIN channel_domain ON(channel_domain.channel_id = channel.id)

        WHERE channel.type_id = :type
        GROUP BY channel.id, channel_domain.url
        ORDER BY LENGTH(url) ASC
        LIMIT 1
        ',
        ['type' => Uuid::fromHexToBytes(Defaults::SALES_CHANNEL_TYPE_STOREFRONT)]
    );
}

$ids = $connection->fetchFirstColumn('SELECT LOWER(HEX(id)) FROM category WHERE level <= 3 ' . $limit);

$listings = $connection->fetchFirstColumn(
    '
    SELECT
        CONCAT(\'/\', seo_path_info)
    FROM seo_url
    INNER JOIN category ON(category.id = seo_url.foreign_key)
    WHERE
        route_name = \'frontend.navigation.page\' AND
        is_deleted = 0 AND
        is_canonical = 1 AND
        foreign_key IN (:ids)
',
    [
        'ids' => Uuid::fromHexToBytesList($ids),
    ],
    ['ids' => ArrayParameterType::BINARY]
);

$storeApiCategories = $connection->fetchFirstColumn('SELECT LOWER(HEX(id)) FROM category WHERE level <= 5 ' . $limit);

$limit = $env['product_page_limit'] !== null ? ' LIMIT ' . (int)$env['product_page_limit'] : '';
$details = $connection->fetchFirstColumn('
SELECT
  CONCAT(\'/\', seo_path_info)
FROM seo_url
    INNER JOIN product ON(product.id = seo_url.foreign_key AND product.version_id = :versionId)
    INNER JOIN product_visibility ON(product_visibility.product_id = product.id AND product_visibility.product_version_id = :versionId AND product_visibility.channel_id = :channelId)
WHERE
  route_name = \'frontend.detail.page\' AND
  is_deleted = 0 AND
  is_canonical = 1
GROUP BY product.id
' . $limit, [
    'versionId' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION),
    'channelId' => Uuid::fromHexToBytes($channel['id']),
]);

$keywords = array_map(static function (string $term) {
    $terms = explode(' ', $term);

    return array_filter($terms, static fn(string $split) => mb_strlen($split) >= 4);
}, $connection->fetchFirstColumn('SELECT name FROM product_translation WHERE name IS NOT NULL ' . $limit));

$keywords = array_values(array_unique(array_merge(...$keywords)));

$products = $connection->fetchAllAssociative('SELECT LOWER(HEX(id)) as id, product_number as productNumber FROM product ' . $limit);

$properties = $connection->fetchAllAssociative('SELECT LOWER(HEX(id)) as id FROM property_group_option LIMIT 300');

$media = $connection->fetchAllAssociative('SELECT LOWER(HEX(id)) as mediaId FROM media LIMIT 100');

$categories = $connection->fetchAllAssociative('SELECT LOWER(HEX(id)) as id FROM category WHERE child_count <= 1 LIMIT 100');

$taxId = $connection->fetchOne('SELECT LOWER(HEX(id)) FROM tax LIMIT 1');

$advertisements = $connection->fetchAllAssociative(
    '
    SELECT product_number as number, CONCAT(\'/\', seo_path_info) as url
    FROM product
       INNER JOIN seo_url
          ON seo_url.route_name = \'frontend.detail.page\' AND is_deleted = 0 AND is_canonical = 1
    WHERE is_closeout = 0 AND min_purchase = 1
    LIMIT ' . (int)$env['advertisements']
);

$connection->executeStatement(
    'UPDATE channel SET footer_category_version_id = :version, footer_category_id = (SELECT id FROM category WHERE child_count > 0 AND parent_id IS NOT NULL ORDER BY child_count DESC LIMIT 1) WHERE footer_category_id IS NULL',
    ['version' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION)]
);

$connection->executeStatement(
    'UPDATE channel SET service_category_version_id = :version, service_category_id = (SELECT id FROM category WHERE child_count > 0 AND parent_id IS NOT NULL ORDER BY child_count DESC LIMIT 1) WHERE service_category_id IS NULL',
    ['version' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION)]
);

if (empty($channel)) {
    throw new RuntimeException('No frontendchannel found');
}

$channel['salutationId'] = $connection->fetchOne('SELECT LOWER(HEX(id)) FROM salutation LIMIT 1');

$channel['domain'] = $connection->fetchOne('SELECT url FROM channel_domain WHERE channel_id = :id', ['id' => Uuid::fromHexToBytes($channel['id'])]);

$channel['currencies'] = $connection->fetchFirstColumn('SELECT LOWER(HEX(currency_id)) FROM channel_currency WHERE channel_id = :id', ['id' => Uuid::fromHexToBytes($channel['id'])]);

$channel['languages'] = $connection->fetchFirstColumn('SELECT LOWER(HEX(language_id)) FROM channel_language WHERE channel_id = :id', ['id' => Uuid::fromHexToBytes($channel['id'])]);

if (empty($details)) {
    throw new RuntimeException('No product urls found');
}
if (empty($listings)) {
    throw new RuntimeException('No listing urls found');
}
if (empty($products)) {
    throw new RuntimeException('No product numbers found');
}

echo 'Collected: ' . count($listings) . ' listing urls' . \PHP_EOL;
$fs->dumpFile(__DIR__ . '/fixtures/listing_urls.json', json_encode($listings, \JSON_THROW_ON_ERROR));

echo 'Collected: ' . count($details) . ' product urls' . \PHP_EOL;
$fs->dumpFile(__DIR__ . '/fixtures/product_urls.json', json_encode($details, \JSON_THROW_ON_ERROR));

echo 'Collected: ' . count($channel) . 'channels' . \PHP_EOL;
$fs->dumpFile(__DIR__ . '/fixtures/channel.json', json_encode($channel, \JSON_THROW_ON_ERROR));

echo 'Collected: ' . count($keywords) . ' keywords' . \PHP_EOL;
$fs->dumpFile(__DIR__ . '/fixtures/keywords.json', json_encode($keywords, \JSON_THROW_ON_ERROR));

echo 'Collected: ' . count($products) . ' products' . \PHP_EOL;
$fs->dumpFile(__DIR__ . '/fixtures/products.json', json_encode($products, \JSON_THROW_ON_ERROR));

echo 'Collected: ' . count($storeApiCategories) . ' categories for frontend-api' . \PHP_EOL;
$fs->dumpFile(__DIR__ . '/fixtures/categories.json', json_encode($storeApiCategories, \JSON_THROW_ON_ERROR));

echo 'Collected: ' . count($categories) . ' categories' . \PHP_EOL;
echo 'Collected: ' . count($properties) . ' properties' . \PHP_EOL;
echo 'Collected: ' . count($media) . ' media' . \PHP_EOL;

$fs->dumpFile(__DIR__ . '/fixtures/imports.json', json_encode([
    'categories' => $categories,
    'media' => $media,
    'properties' => $properties,
    'channelId' => $channel['id'],
    'taxId' => $taxId,
], \JSON_THROW_ON_ERROR));

echo 'Collected: ' . count($advertisements) . ' advertisements' . \PHP_EOL;
$fs->dumpFile(__DIR__ . '/fixtures/advertisements.json', json_encode($advertisements, \JSON_THROW_ON_ERROR));
