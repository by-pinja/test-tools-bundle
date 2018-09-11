<?php
declare(strict_types = 1);

namespace Protacon\Bundle\TestToolsBundle\Util;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use function array_map;
use function iterator_to_array;
use function count;

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
     * @var string
     */
    private $projectDir;

    /**
     * PackageManager constructor.
     *
     * @param string $projectDir
     */
    public function __construct(string $projectDir)
    {
        $this->resourcePath = __DIR__ . '/../Resources/package/';
        $this->targetPath = $projectDir . '/vendor-bin/';
        $this->projectDir = $projectDir;
        $this->filesystem = new Filesystem();
    }

    /**
     * @return array|array<string, string>
     */
    public function listPackages(): array
    {
        $packages = [];

        array_map(
            function (SplFileInfo $directory) use (&$packages) {
                $packages[$directory->getFilename()] = $this->getPackageDescription($directory->getFilename());
            },
            iterator_to_array((new Finder())->directories()->depth(0)->in($this->resourcePath))
        );

        return $packages;
    }

    /**
     * @param string $package
     */
    public function addPackage(string $package): void
    {
        $sourcePath = $this->resourcePath . $package;
        $targetPath = $this->targetPath . $package;

        $finder = (new Finder())->files()->depth(0)->ignoreDotFiles(false)->in($sourcePath);

        /**
         * Closure to copy all specified package files to current project
         *
         * @param \Symfony\Component\Finder\SplFileInfo $file
         */
        $iterator = function (SplFileInfo $file) use ($sourcePath, $targetPath): void {
            $this->filesystem->copy(
                $sourcePath . DIRECTORY_SEPARATOR . $file->getFilename(),
                $targetPath . DIRECTORY_SEPARATOR . $file->getFilename()
            );
        };

        array_map($iterator, iterator_to_array($finder));

        $this->configurePackage($package);
    }

    /**
     * @param string $package
     *
     * @return bool
     */
    public function exists(string $package): bool
    {
        return $this->filesystem->exists($this->targetPath . $package);
    }

    /**
     * @param string $package
     */
    private function configurePackage(string $package): void
    {
        $configDir = $this->resourcePath . $package . '/config/';

        if (!$this->filesystem->exists($configDir)) {
            return;
        }

        $finder = (new Finder())->files()->depth(0)->ignoreDotFiles(false)->in($configDir);

        /**
         * Closure to copy specified package config file(s) to current project root.
         *
         * @param SplFileInfo $file
         */
        $iterator = function (SplFileInfo $file) use ($configDir): void {
            $this->filesystem->copy($configDir . $file->getFilename(),$this->projectDir . $file->getFilename());
        };

        array_map($iterator, iterator_to_array($finder));
    }

    /**
     * @param string $package
     *
     * @return string
     */
    private function getPackageDescription(string $package): string
    {
        $readmes = (new Finder())->files()->in($this->resourcePath . $package)->name('README.md');

        foreach ($readmes as $readme) {
            /** @var SplFileInfo $readme */
            preg_match('/#{1,3}\s' . $package . '\s+([^\n]*)/mi', $readme->getContents(), $matches);

            if (count($matches) === 2) {
                return $matches[1];
            }
        }

        return '';
    }
}