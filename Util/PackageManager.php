<?php
declare(strict_types = 1);

namespace Protacon\Bundle\TestToolsBundle\Util;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use function array_map;
use function iterator_to_array;

/**
 * Class PackageManager
 *
 * @package Protacon\Bundle\TestToolsBundle\Util
 */
class PackageManager
{
    /**
     * @var string
     */
    private $resourcePath;

    /**
     * @var string
     */
    private $targetPath;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * PackageManager constructor.
     *
     * @param string $targetPath
     */
    public function __construct(string $targetPath)
    {
        $this->resourcePath = __DIR__ . '/../Resources/package/';
        $this->targetPath = $targetPath;
        $this->filesystem = new Filesystem();
    }

    /**
     * @return array
     */
    public function listPackages(): array
    {
        return array_map(
            function (SplFileInfo $directory) { return $directory->getFilename(); },
            iterator_to_array((new Finder())->directories()->depth(0)->in($this->resourcePath))
        );
    }

    /**
     * @param string $package
     */
    public function addPackage(string $package): void
    {
        $sourcePath = $this->resourcePath . $package;
        $targetPath = $this->targetPath . $package;

        $iterator = (new Finder())->files()->depth(0)->ignoreDotFiles(false)->in($sourcePath);

        $copy = function (SplFileInfo $file) use ($sourcePath, $targetPath) {
            $this->filesystem->copy($sourcePath . '/' . $file->getFilename(), $targetPath . '/' . $file->getFilename());
        };

        array_map($copy, iterator_to_array($iterator));
    }

    /**
     * @return void
     */
    public function addReadme(): void
    {
        $this->filesystem->copy($this->resourcePath . '/README.md', $this->targetPath . '/README.md');
    }
}