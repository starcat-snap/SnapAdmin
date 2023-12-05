<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\TestCaseBase;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Test\TestDefaults;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait CountryAddToChannelTestBehaviour
{
    abstract protected static function getContainer(): ContainerInterface;

    abstract protected function getValidCountryId(?string $channelId = TestDefaults::SALES_CHANNEL): string;

    /**
     * @param array<string> $additionalCountryIds
     */
    protected function addCountriesToChannel(array $additionalCountryIds = [], string $channelId = TestDefaults::SALES_CHANNEL): void
    {
        /** @var EntityRepository $channelRepository */
        $channelRepository = $this->getContainer()->get('channel.repository');

        $countryIds = array_merge([
            ['id' => $this->getValidCountryId($channelId)],
        ], array_map(static fn(string $countryId) => ['id' => $countryId], $additionalCountryIds));

        $channelRepository->update([[
            'id' => $channelId,
            'countries' => $countryIds,
        ]], Context::createDefaultContext());
    }
}
