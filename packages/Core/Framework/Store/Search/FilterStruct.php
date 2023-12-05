<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Search;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @internal
 */
#[Package('services-settings')]
abstract class FilterStruct extends Struct
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @return EqualsFilterStruct|MultiFilterStruct
     */
    public static function fromArray(array $data): FilterStruct
    {
        $type = $data['type'];

        if ($type === 'multi') {
            return MultiFilterStruct::fromArray($data);
        }

        if ($type === 'equals') {
            return EqualsFilterStruct::fromArray($data);
        }

        throw new \InvalidArgumentException('Type ' . $type . ' not allowed');
    }

    /**
     * @return array<string, string>
     */
    abstract public function getQueryParameter(): array;

    public function getType(): string
    {
        return $this->type;
    }
}
