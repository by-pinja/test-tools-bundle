<?php

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
use function count;
use function in_array;
use function array_merge;
use function array_keys;

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
     * @var string
     */
    private $projectDir;

    /**
     * @inheritdoc
     */
    public function __construct(ComposerManager $composerManager, PackageManager $packageManager, string $projectDir)
    {
        parent::__construct();

        $this->packageManager = $packageManager;
        $this->composerManager = $composerManager;
        $this->projectDir = $projectDir;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setDescription('Initialize test tools');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);

        // Make sure that main composer.json contains all needed scripts
        $this->composerManager->initialize();

        $packages = $this->promptPackages($io);

        $this->packageManager->addReadme();

        foreach ($packages as $package) {
            if ($this->packageManager->exists($package)) {
                $confirm = new ConfirmationQuestion(
                    'Package "' . $package . '" has existing configuration. Do you want to override?'
                );

                if (!$io->askQuestion($confirm)) {
                    continue;
                }
            }

            $this->packageManager->addPackage($package);
        }

        $io->success(count($packages) . ' packages added. Refer to vendor-bin/{package}/README.md to learn more');

        $question = new ConfirmationQuestion('Do you want to install packages now?');

        if ($io->askQuestion($question)) {
            $command = [
                'composer',
                'run-script',
                'vendor-bin-install',
            ];

            $process = new Process($command, $this->projectDir);
            $process->enableOutput();
            $process->setTimeout(null);

            $process->run(function($type, $output) use ($io) {
                $io->write($output);
            });
        }

        return null;
    }

    /**
     * @param SymfonyStyle $io
     *
     * @return array
     */
    private function promptPackages(SymfonyStyle $io): array
    {
        $packages = $this->packageManager->listPackages();

        $choices = array_merge($packages, array('all' => 'Configure all above'));

        $question = new ChoiceQuestion(
            'Which packages you would like to configure?',
            $choices,
            'all'
        );

        $question->setMultiselect(true);
        $question->setAutocompleterValues(array_keys($choices));

        $result = $io->askQuestion($question);

        if (in_array('all', $result, true)) {
            $result = array_keys($packages);
        }

        return $result;
    }
}
