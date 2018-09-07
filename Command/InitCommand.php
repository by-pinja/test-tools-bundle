<?php

namespace Protacon\Bundle\TestToolsBundle\Command;

use Protacon\Bundle\TestToolsBundle\Util\ComposerManager;
use Protacon\Bundle\TestToolsBundle\Util\PackageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function count;

/**
 * Class InitCommand
 *
 * @package Protacon\Bundle\TestToolsBundle\Command
 */
class InitCommand extends Command
{
    /**
     * @var PackageManager
     */
    private $packageManager;

    /**
     * @var ComposerManager
     */
    private $composerManager;

    /**
     * @inheritdoc
     */
    public function __construct(ComposerManager $composerManager, PackageManager $packageManager)
    {
        parent::__construct();

        $this->packageManager = $packageManager;
        $this->composerManager = $composerManager;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        // TODO
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);

        // Make sure that main composer.json contains all needed scripts
        $this->composerManager->initialize();

        $packages = $this->packageManager->listPackages();
        $this->packageManager->addReadme();

        foreach ($packages as $package) {
            $this->packageManager->addPackage($package);
        }

        $io->success(count($packages) . ' packages added. Refer to vendor-bin/{package}/README.md to learn more');

        return null;
    }
}
