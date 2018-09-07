<?php
declare(strict_types = 1);

namespace Protacon\Bundle\TestToolsBundle\Util;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
     * @var Finder
     */
    private $finder;

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
        $this->finder = new Finder();
    }

    /**
     * @return array
     */
    public function listPackages(): array
    {
        $finder = $this->finder->directories()->in($this->resourcePath);

        $packages = [];

        foreach ($finder as $directory) {
            /** @var SplFileInfo $directory */
            $packages[] = $directory->getFilename();
        }

        return $packages;
    }

    /**
     * @param string $package
     */
    public function addPackage(string $package): void
    {
        $sourcePath = $this->resourcePath . $package;
        $targetPath = $this->targetPath . $package;

        $this->filesystem->mkdir($targetPath, 0777);

        $this->filesystem->copy($sourcePath . '/composer.json', $targetPath . '/composer.json');
        $this->filesystem->copy($sourcePath . '/.gitignore', $targetPath . '/.gitignore');
    }

    /**
     * @return void
     */
    public function makeTargetDirectory(): void
    {
        if ($this->filesystem->exists($this->targetPath)) {
            return;
        }

        $this->filesystem->mkdir($this->targetPath, 0777);
    }
}