<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Filesystem\Plugin;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class CopyBatchInput
{
    /**
     * @var string|resource
     */
    private $sourceFile;

    /**
     * @param string|resource $sourceFile
     * @param array<string> $targetFiles
     */
    public function __construct(
        $sourceFile,
        private readonly array $targetFiles
    ) {
        if (!\is_resource($sourceFile) && !\is_string($sourceFile)) {
            throw new \InvalidArgumentException(sprintf(
                'CopyBatchInput expects first parameter to be either a resource or the filepath as a string, "%s" given.',
                \gettype($sourceFile)
            ));
        }
        $this->sourceFile = $sourceFile;
    }

    /**
     * @return string|resource
     */
    public function getSourceFile()
    {
        return $this->sourceFile;
    }

    /**
     * @return array<string>
     */
    public function getTargetFiles(): array
    {
        return $this->targetFiles;
    }
}
