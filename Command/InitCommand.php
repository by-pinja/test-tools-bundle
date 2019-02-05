<?php
declare(strict_types = 1);

namespace Protacon\Bundle\TestToolsBundle\Command;

use Protacon\Bundle\TestToolsBundle\Util\ComposerManager;
use Protacon\Bundle\TestToolsBundle\Util\PackageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use function array_filter;
use function array_keys;
use function array_merge;
use function count;
use function in_array;

/**
 * Class InitCommand
 *
 * @package Protacon\Bundle\TestToolsBundle\Command
 */
class InitCommand extends Command
{
    /**
     * @var string The default command name
     */
    public static $defaultName = 'test-tools:check';

    /**
     * @var PackageManager
     */
    private $packageManager;

    /**
     * @var ComposerManager
     */
    private $composerManager;

    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * {@inheritdoc}
     *
     * @param ComposerManager $composerManager
     * @param PackageManager  $packageManager
     * @param string          $projectDir
     */
    public function __construct(ComposerManager $composerManager, PackageManager $packageManager, string $projectDir)
    {
        parent::__construct();

        $this->packageManager = $packageManager;
        $this->composerManager = $composerManager;
        $this->projectDir = $projectDir;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setDescription('Initialize test tools');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle(
            $input,
            $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output
        );

        // Make sure that main composer.json contains all needed scripts
        $this->composerManager->initialize();

        // Add packages
        $packages = $this->addPackages();

        // Make install if packages has been add
        if (count($packages) > 0) {
            $this->makeInstall();
        }

        $this->io->success(count($packages) . ' packages added. Refer to vendor-bin/{package}/README.md to learn more');

        return null;
    }

    /**
     * @return string[]
     */
    private function addPackages(): array
    {
        /**
         * Closure to add single package to current project
         *
         * @param string $package
         *
         * @return string|null
         */
        $addPackage = function (string $package): ?string {
            if ($this->packageManager->exists($package)) {
                $confirm = new ConfirmationQuestion(
                    'Package "' . $package . '" has existing configuration. Do you want to override?'
                );

                if (!$this->io->askQuestion($confirm)) {
                    return null;
                }
            }

            $this->packageManager->addPackage($package);

            return $package;
        };

        return array_filter(array_map($addPackage, $this->promptPackages()));
    }

    private function makeInstall(): void
    {
        $question = new ConfirmationQuestion('Do you want to install packages now?');

        if ($this->io->askQuestion($question)) {
            $command = [
                'composer',
                'run-script',
                'vendor-bin-install',
            ];

            $process = new Process($command, $this->projectDir);
            $process->enableOutput();
            $process->setTimeout(null);

            $process->run(
                function ($type, $output): void {
                    $this->io->write($output);
                }
            );
        }
    }

    /**
     * @return string[]
     */
    private function promptPackages(): array
    {
        $packages = $this->packageManager->listPackages();

        $choices = array_merge($packages, ['all' => 'Configure all above']);

        $question = new ChoiceQuestion(
            'Which packages you would like to configure?',
            $choices,
            'all'
        );

        $question->setMultiselect(true);
        $question->setAutocompleterValues(array_keys($choices));

        $result = $this->io->askQuestion($question);

        if (in_array('all', $result, true)) {
            $result = array_keys($packages);
        }

        return $result;
    }
}
