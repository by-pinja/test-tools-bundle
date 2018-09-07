<?php

namespace Protacon\Bundle\TestToolsBundle\Command;

use Protacon\Bundle\TestToolsBundle\Util\PackageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
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
     * @inheritdoc
     */
    public function __construct(PackageManager $packageManager)
    {
        parent::__construct();

        $this->packageManager = $packageManager;
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

        $packages = $this->promptPackages($io);

        $this->packageManager->addReadme();

        foreach ($packages as $package) {
            $this->packageManager->addPackage($package);
        }

        $io->success(count($packages) . ' packages added. Refer to vendor-bin/{package}/README.md to learn more');

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
