<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Exception;

use SnapAdmin\Core\Content\Media\MediaException;
use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated tag:v6.6.0 - will be removed, use MediaException::strategyNotFound instead
 */

class StrategyNotFoundException extends MediaException
{
    public function __construct(string $strategyName)
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.6.0.0', 'use MediaException::strategyNotFound instead')
        );

        parent::__construct(
            Response::HTTP_NOT_FOUND,
            self::MEDIA_STRATEGY_NOT_FOUND,
            'No Strategy with name "{{ strategyName }}" found.',
            ['strategyName' => $strategyName]
        );
    }

    public function getErrorCode(): string
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedMethodMessage(self::class, __METHOD__, 'v6.6.0.0', 'use MediaException::strategyNotFound instead')
        );

        return 'CONTENT__MEDIA_STRATEGY_NOT_FOUND';
    }
}
